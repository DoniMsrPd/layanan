<?php

namespace App\Http\Controllers\Setting;

use App\Http\Controllers\Controller;
use App\Models\Setting\ServiceCatalog;
use App\Models\Setting\ServiceCatalogDetail;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;

class ServiceCatalogDetailController extends Controller
{

    private static $response = [
        'success' => false,
        'data'    => null,
        'message' => null
    ];

    public function dataTables()
    {
        $data = ServiceCatalogDetail::whereNull('DeletedAt')->where('ServiceCatalogId', request()->serviceCatalogId);
        return DataTables::of($data)->addColumn('action', function ($data) {

            $editButton = '';
            $deleteButton = '';
            if(request()->user()->can('service-catalog-sla.update'))
                $editButton = '<a href="' . route('setting.service-catalog-detail.edit', $data->Id) . '" class="mb-2 mr-2 btn btn-warning btn-sm" title="Ubah" title-pos="up">'.btnEdit().'</a>';
            if(request()->user()->can('service-catalog-sla.delete'))
                $deleteButton= '<a data-id="' . $data->Id . ' " data-url="/setting/service-catalog-detail/' . $data->Id . ' " class="mb-2 mr-2 btn btn-danger btn-sm deleteData" data-title ="Data" title="Hapus" title-pos="up">'.btnDelete().'</a>';
            return '<span class="btn-group" role="group"> <a href="' . route('setting.service-catalog-detail.show', $data->Id) . '" class="mb-2 mr-2 btn btn-success btn-sm" title="Ubah" title-pos="up">'.btnSearch().'</a>'.$editButton.''.$deleteButton.'</span>';
        })->addColumn('pilih', function ($data) {
            $button = '<button href="#" class="mb-2 mr-2 btn btn-primary btn-sm pilih-sla" data-id="' . $data->Id . '" data-nama="' . $data->Nama . '"  title-pos="up"><svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-share"><path d="M4 12v8a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2v-8"></path><polyline points="16 6 12 2 8 6"></polyline><line x1="12" y1="2" x2="12" y2="15"></line></svg></button>';
            return '<span class="btn-group" role="group">' . $button . '</span>';
        })->addColumn('mobile', function ($data) {
            $button = '<button href="#" class="btn btn-primary btn-sm pilih-sla" data-id="' . $data->Id . '" data-nama="' . $data->Nama . '"  title-pos="up"><svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-share"><path d="M4 12v8a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2v-8"></path><polyline points="16 6 12 2 8 6"></polyline><line x1="12" y1="2" x2="12" y2="15"></line></svg></button>';
            $button =  '<span class="btn-group" role="group">' . $button . '</span>';
            return "$data->Nama <br> $button";
        })->rawColumns(['action','pilih','mobile'])->make(true);
    }
    public function create(ServiceCatalog $serviceCatalog)
    {
        $this->authorize('service-catalog-sla.create');
        $data = (object) [
            'method' => 'POST',
            'action' => '/setting/service-catalog-detail',
            'title' => 'TAMBAH SLA',
            'catalog' => $serviceCatalog,
            'catalogDetail' => [],
            'KdUnitOrgLayanan' => kdUnitOrgOwner(),
        ];

        return view('setting.service-catalog-detail.form', compact('data'));
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    function inputServiceCatalogDetail($request, $update = false)
    {
        $inputServiceCatalogDetail = [
            'ServiceCatalogId' => $request->serviceCatalogId,
            'NoUrut' => $request->noUrut,
            'Nama' => $request->nama,
            'NormaWaktu' => $request->normaWaktu,
            'Waktu' => $request->waktu,
            'Limit' => $request->limit,
            'JenisPerhitungan' => $request->jenisPerhitungan,
            'JenisLayanan' => $request->jenisLayanan,
        ];
        return $inputServiceCatalogDetail;
    }
    public function store(Request $request)
    {
        $this->authorize('service-catalog-sla.create');
        DB::beginTransaction();
        try {
            $inputServiceCatalogDetail = $this->inputServiceCatalogDetail($request);
            $inputServiceCatalogDetail['Id'] = uuid();
            $inputServiceCatalogDetail['CreatedAt'] = Carbon::now();
            $inputServiceCatalogDetail['CreatedBy'] = auth()->user()->NIP;
            $data = ServiceCatalogDetail::create($inputServiceCatalogDetail);
            DB::commit();
            logActivity('default', 'Service Catalog')->log("Add SLA ".$inputServiceCatalogDetail['Id']);
            $msg = 'Tambah SLA Berhasil';
            $type = "success";
        } catch (\Exception $e) {
            DB::rollback();
            $type = "warning";
            $msg = $e->getMessage();
            \Log::error($e->getTraceAsString());
        }

        return redirect(url('/setting/service-catalog/'.$request->serviceCatalogId))->with('flash_message', $msg)
            ->with('flash_type', $type);
    }
    public function edit(ServiceCatalogDetail $serviceCatalogDetail, $show = false)
    {
        $this->authorize('service-catalog-sla.read');
        $data = (object) [
            'method' => 'PATCH',
            'action' => "/setting/service-catalog-detail/$serviceCatalogDetail->Id",
            'title' => ($show) ? 'EDIT SLA':'DETAIL SLA',
            'catalog' => $serviceCatalogDetail->catalog,
            'catalogDetail' => $serviceCatalogDetail,
            'readonly' => ($show) ? 'readonly' : null,
            'KdUnitOrgLayanan' => kdUnitOrgOwner(),

        ];

        return view('setting.service-catalog-detail.form', compact('data'));
    }
    public function update(Request $request, ServiceCatalogDetail $serviceCatalogDetail)
    {
        $this->authorize('service-catalog-sla.update');
        try {
            DB::beginTransaction();
            $inputServiceCatalogDetail = $this->inputServiceCatalogDetail($request, true);
            $inputServiceCatalogDetail['UpdatedAt'] = Carbon::now();
            $inputServiceCatalogDetail['UpdatedBy'] = auth()->user()->NIP;
            ServiceCatalogDetail::where('Id', $serviceCatalogDetail->Id)->update($inputServiceCatalogDetail);
            DB::commit();
            logActivity('default', 'Service Catalog')->log("Add SLA ".$serviceCatalogDetail->Id);
            $msg = 'Edit SLA Berhasil';
            $type = "success";
        } catch (\Throwable $e) {
            DB::rollback();
            $type = "warning";
            $msg = $e->getMessage();
            \Log::error($e->getTraceAsString());
        }

        return redirect(url('/setting/service-catalog/'.$request->serviceCatalogId))->with('flash_message', $msg)
            ->with('flash_type', $type);
    }
    public function show(ServiceCatalogDetail $serviceCatalogDetail)
    {
        return $this->edit($serviceCatalogDetail, true);
    }
    public function destroy(ServiceCatalogDetail $serviceCatalogDetail)
    {
        $this->authorize('service-catalog-sla.delete');
        $response = self::$response;
        $delete = false;

        try {
            $serviceCatalogDetail->DeletedAt = Carbon::now()->format('Y-m-d H:i:s');
            $serviceCatalogDetail->DeletedBy = auth()->user()->NIP;
            $delete = $serviceCatalogDetail->save();
            $response['success'] = $delete;
            $response['message'] = $delete ? 'Berhasil Hapus Data' : 'Gagal Hapus Data';
            logActivity('default', 'Service Catalog')->log("Add SLA ".$serviceCatalogDetail->Id);

            return response()->json($response);
        } catch (\Exception $e) {
            $response['success'] = false;
            $response['message'] = $e->getMessage();
            return response()->json($response);
        }
    }
}
