<?php

namespace App\Http\Controllers\Setting;

use App\Http\Controllers\Controller;
use App\Models\Setting\MstTematik;
use App\Models\Setting\ServiceCatalog;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;

class ServiceCatalogController extends Controller
{

    private static $response = [
		'success' => false,
		'data'    => null,
		'message' => null
	];
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        logActivity('default', 'Service Catalog')->log("View Service Catalog");
        $this->authorize('service-catalog.read');
        $data = (object) [
            'KdUnitOrgLayanan' => kdUnitOrgOwner(),
        ];
        return view('setting.service-catalog.index', compact('data'));
    }

    public function dataTables(Request $request)
    {
        $data = ServiceCatalog::with('owner')->whereNull('DeletedAt')->where('KdUnitOrgOwnerLayanan',kdUnitOrgOwner());
        if($request->aktif){
            $data->whereRaw('TglEnd is null or TglEnd > getdate()');
        }
        return DataTables::of($data)->addColumn('action', function ($data) {
            $editButton = '';
            $deleteButton = '';
            if(request()->user()->can('service-catalog.update'))
                $editButton = '<a href="' . route('setting.service-catalog.edit', $data->Id) . '" class="mb-2 mr-2 btn btn-warning btn-sm" title="Ubah" title-pos="up"><svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-edit-2"><path d="M17 3a2.828 2.828 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5L17 3z"></path></svg></a>';
            if(request()->user()->can('service-catalog.delete'))
                $deleteButton = '<a data-id="' . $data->Id . ' " data-url="/setting/service-catalog/' . $data->Id . ' " class="mb-2 mr-2 btn btn-danger btn-sm deleteData" data-title ="Data" title="Hapus" title-pos="up"><svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-trash-2"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path><line x1="10" y1="11" x2="10" y2="17"></line><line x1="14" y1="11" x2="14" y2="17"></line></svg></a>';
            return '<span class="btn-group" role="group">'.$editButton.''.$deleteButton.'</span>';
        })->addColumn('pilih', function ($data) {
            $button = '<button href="#" class="mb-2 mr-2 btn btn-primary btn-sm pilih-service-catalog" data-id="' . $data->Id . '" data-kode="' . $data->Kode . '" data-nama="' . $data->Nama . '"  title-pos="up"><svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-share"><path d="M4 12v8a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2v-8"></path><polyline points="16 6 12 2 8 6"></polyline><line x1="12" y1="2" x2="12" y2="15"></line></svg></button>';
            return '<span class="btn-group" role="group">' . $button . '</span>';
        })->addColumn('mobile', function ($data) {
            $button = '<button href="#" class="btn btn-primary btn-sm pilih-service-catalog" data-id="' . $data->Id . '" data-kode="' . $data->Kode . '" data-nama="' . $data->Nama . '"  title-pos="up"><svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-share"><path d="M4 12v8a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2v-8"></path><polyline points="16 6 12 2 8 6"></polyline><line x1="12" y1="2" x2="12" y2="15"></line></svg></button>';
            $button =  '<span class="btn-group" role="group">' . $button . '</span>';
            return "$data->Kode <br> $data->Nama <br> $button";

        })->rawColumns(['action','pilih','mobile'])->make(true);
    }
    public function create()
    {
        $this->authorize('service-catalog.create');
        $data = (object) [
            'method' => 'POST',
            'action' => '/setting/service-catalog',
            'title' => 'TAMBAH SERVICE CATALOG',
            'catalog' => [],
            'KdUnitOrgLayanan' => kdUnitOrgOwner(),
        ];

        return view('setting.service-catalog.form',compact('data'));
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    function inputCatalog($request, $update = false){
        $inputCatalog = [
            'Kode' => $request->kode,
            'Nama' => $request->nama,
            'IsPeminjaman' => $request->isPeminjaman,
            'IsPersediaan' => $request->isPersediaan,
            'IsPerbaikan' => $request->isPerbaikan,
            'TglStart' => $request->tglStart,
            'TglEnd' => $request->tglEnd,
        ];
        return $inputCatalog;

    }
    public function store(Request $request)
    {
        $this->authorize('service-catalog.create');
        DB::beginTransaction();
        try {
            $inputCatalog = $this->inputCatalog($request);
            $inputCatalog['Id'] = uuid();
            $inputCatalog['CreatedAt'] = Carbon::now();
            $inputCatalog['CreatedBy'] = auth()->user()->NIP;
            $inputCatalog['KdUnitOrgOwnerLayanan'] = $request->KdUnitOrgOwnerLayanan;
            $data = ServiceCatalog::create($inputCatalog);
            logActivity('default', 'Service Catalog')->log("Add Service Catalog ".$inputCatalog['Id']);
            DB::commit();
            $msg = 'Tambah Catalog Berhasil';
            $type = "success";
        } catch (\Exception $e) {
            DB::rollback();
            $type = "warning";
            $msg = $e->getMessage();
            \Log::error($e->getTraceAsString());
        }

        return redirect(url('setting/service-catalog'))->with('flash_message', $msg)
            ->with('flash_type', $type);
    }
    public function edit(ServiceCatalog $serviceCatalog, $show = false)
    {

        $this->authorize('service-catalog.read');
        $data = (object) [
            'method' => 'PATCH',
            'action' => "/setting/service-catalog/$serviceCatalog->Id",
            'title' => 'EDIT SERVICE CATALOG',
            'catalog' => $serviceCatalog,
            'KdUnitOrgLayanan' => kdUnitOrgOwner(),
        ];

        return view('setting.service-catalog.form',compact('data'));
    }
    public function update(Request $request, ServiceCatalog $serviceCatalog)
    {
        $this->authorize('service-catalog.update');
        try {
            DB::beginTransaction();
            $inputCatalog = $this->inputCatalog($request, true);
            $inputCatalog['UpdatedAt'] = Carbon::now();
            $inputCatalog['UpdatedBy'] = auth()->user()->NIP;
            ServiceCatalog::where('Id', $serviceCatalog->Id)->update($inputCatalog);
            DB::commit();
            logActivity('default', 'Service Catalog')->log("Update Service Catalog ".$serviceCatalog->Id);
            $msg = 'Edit Catalog Berhasil';
            $type = "success";
        } catch (\Throwable $e) {
            DB::rollback();
            $type = "warning";
            $msg = $e->getMessage();
            \Log::error($e->getTraceAsString());
        }

        return redirect(url('setting/service-catalog'))->with('flash_message', $msg)
            ->with('flash_type', $type);
    }
    public function show(ServiceCatalog $serviceCatalog){
        $this->authorize('service-catalog.read');
        $data = (object) [
            'serviceCatalog' => $serviceCatalog,
            'mstTematik' => MstTematik::whereNull('DeletedAt')->get(),
            'KdUnitOrgLayanan' => kdUnitOrgOwner(),
        ];

        logActivity('default', 'Service Catalog')->log("Show Service Catalog ".$serviceCatalog->Id);
        return view('setting.service-catalog.show',compact('data'));
    }
    public function destroy(ServiceCatalog $serviceCatalog)
    {
        $this->authorize('service-catalog.delete');
        $response = self::$response;
        $delete = false;

        try {
            $serviceCatalog->DeletedAt = Carbon::now()->format('Y-m-d H:i:s');
            $serviceCatalog->DeletedBy = auth()->user()->NIP;
            $delete = $serviceCatalog->save();
            $response['success'] = $delete;
            $response['message'] = $delete ? 'Berhasil Hapus Data' : 'Gagal Hapus Data';

            logActivity('default', 'Service Catalog')->log("Delete Service Catalog ".$serviceCatalog->Id);
            return response()->json($response);

        } catch (\Exception $e) {
            $response['success'] = false;
            $response['message'] = $e->getMessage();
            return response()->json($response);
        }
    }
    public function check(Request $request)
    {
        $cekData = ServiceCatalog::where('Kode',$request->kode)->where('TglStart',$request->tglStart)->where('TglEnd',$request->tglEnd)->first();
        $response['success'] = empty($cekData);

        return response()->json($response);
    }
}
