<?php

namespace App\Http\Controllers\Setting;

use App\Http\Controllers\Controller;
use App\Models\Setting\MstTematik;
use App\Models\Setting\ServiceCatalogTematik;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;

class MstTematikController extends Controller
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
        $this->authorize('master-tematik.read');
        logActivity('default', ' Master Tematik')->log("View Master Tematik ");
        return view('setting.mst-tematik.index');
    }

    public function dataTables(Request $request)
    {
        $data = MstTematik::whereNull('DeletedAt');
        return DataTables::of($data)->addColumn('action', function ($data) {
            $editButton = '';
            $deleteButton = '';
            if(request()->user()->can('master-tematik.update'))
                $editButton = '<a href="' . route('setting.master-tematik.edit', $data->Id) . '" class="mb-2 mr-2 btn btn-warning btn-sm" title="Ubah" title-pos="up"><i class="icon-feather-edit-2"></i></a>';
            if(request()->user()->can('master-tematik.delete'))
                $deleteButton = '<a data-id="' . $data->Id . ' " data-url="/setting/master-tematik/' . $data->Id . ' " class="mb-2 mr-2 btn btn-danger btn-sm deleteData" data-title ="Data" title="Hapus" title-pos="up">'.btnDelete().'</a>';
            return '<span class="btn-group" role="group">'.$editButton.''.$deleteButton.'</span>';
        })->rawColumns(['action'])->make(true);
    }
    public function create()
    {
        $this->authorize('master-tematik.create');
        $data = (object) [
            'method' => 'POST',
            'action' => '/setting/master-tematik',
            'title' => 'TAMBAH MASTER TEMATIK',
            'mstTematik' => []
        ];

        return view('setting.mst-tematik.form',compact('data'));
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    function inputMstTematik($request, $update = false){
        $inputMstTematik = [
            'Tema' => $request->tema,
            'Keterangan' => $request->keterangan,
        ];
        return $inputMstTematik;

    }
    public function store(Request $request)
    {
        $this->authorize('master-tematik.create');
        DB::beginTransaction();
        try {
            $inputMstTematik = $this->inputMstTematik($request);
            $inputMstTematik['Id'] = uuid();
            $inputMstTematik['CreatedAt'] = Carbon::now();
            $inputMstTematik['CreatedBy'] = auth()->user()->NIP;
            $data = MstTematik::create($inputMstTematik);
            DB::commit();
            logActivity('default', ' Master Tematik')->log("Add Master Tematik ".$inputMstTematik['Id'] );
            $msg = 'Tambah Master Tematik Berhasil';
            $type = "success";
        } catch (\Exception $e) {
            DB::rollback();
            $type = "warning";
            $msg = $e->getMessage();
            \Log::error($e->getTraceAsString());
        }

        return redirect(url('setting/master-tematik'))->with('flash_message', $msg)
            ->with('flash_type', $type);
    }
    public function edit(MstTematik $masterTematik, $show = false)
    {

        $this->authorize('master-tematik.read');
        $data = (object) [
            'method' => 'PATCH',
            'action' => "/setting/master-tematik/$masterTematik->Id",
            'title' => 'EDIT MASTER TEMATIK',
            'mstTematik' => $masterTematik,
        ];

        return view('setting.mst-tematik.form',compact('data'));
    }
    public function update(Request $request, MstTematik $masterTematik)
    {
        $this->authorize('master-tematik.update');
        try {
            DB::beginTransaction();
            $inputMstTematik = $this->inputMstTematik($request, true);
            $inputMstTematik['UpdatedAt'] = Carbon::now();
            $inputMstTematik['UpdatedBy'] = auth()->user()->NIP;
            MstTematik::where('Id', $masterTematik->Id)->update($inputMstTematik);
            DB::commit();
            logActivity('default', ' Master Tematik')->log("Update Master Tematik ".$masterTematik->Id );
            $msg = 'Edit Master Tematik  Berhasil';
            $type = "success";
        } catch (\Throwable $e) {
            DB::rollback();
            $type = "warning";
            $msg = $e->getMessage();
            \Log::error($e->getTraceAsString());
        }

        return redirect(url('setting/master-tematik'))->with('flash_message', $msg)
            ->with('flash_type', $type);
    }
    public function show(MstTematik $masterTematik){
        $this->authorize('master-tematik.read');
        $data = (object) [
            'serviceCatalog' => $masterTematik,
            'mstTematik' => MstTematik::whereNull('DeletedAt')->get()
        ];

        return view('setting.mst-tematik.show',compact('data'));
    }
    public function destroy(MstTematik $masterTematik)
    {
        $this->authorize('master-tematik.delete');
        $response = self::$response;
        $delete = false;

        try {
            $masterTematik->DeletedAt = Carbon::now()->format('Y-m-d H:i:s');
            $masterTematik->DeletedBy = auth()->user()->NIP;
            $delete = $masterTematik->save();
            $response['success'] = $delete;
            $response['message'] = $delete ? 'Berhasil Hapus Data' : 'Gagal Hapus Data';
            logActivity('default', ' Master Tematik')->log("Delete Master Tematik ".$masterTematik->Id );

            return response()->json($response);

        } catch (\Exception $e) {
            $response['success'] = false;
            $response['message'] = $e->getMessage();
            return response()->json($response);
        }
    }

    public function select(Request $request, $dataOnly = false)
    {
        $tematik = ServiceCatalogTematik::where('ServiceCatalogId',$request->serviceCatalogId)->whereNull('DeletedAt')->pluck('MstTematikId')->toArray();
        $query = MstTematik::whereNotIn('Id', $tematik)->whereNull('DeletedAt');

        $data = $query->orderBy('Tema')
            ->pluck('Id', 'Tema');

        if ($dataOnly == true)
            return $data;

        $data->value = $request->value ?? '';
        return view('core._select', compact('data'));
    }
}
