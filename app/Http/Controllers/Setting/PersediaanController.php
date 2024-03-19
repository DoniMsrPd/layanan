<?php

namespace App\Http\Controllers\Setting;

use App\Http\Controllers\Controller;
use App\Models\Setting\Persediaan;
use App\Models\Setting\ServiceCatalogTematik;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;

class PersediaanController extends Controller
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
        $this->authorize('persediaan.read');
        logActivity('default', ' Master Persediaan')->log("View Master Persediaan ");
        return view('setting.persediaan.index');
    }

    public function dataTables(Request $request)
    {
        $data = Persediaan::whereNull('DeletedAt');
        return DataTables::of($data)->addColumn('action', function ($data) {
            $editButton = '';
            $deleteButton = '';
            if(request()->user()->can('persediaan.update'))
                $editButton = '<a href="' . route('setting.persediaan.edit', $data->Id) . '" class="mb-2 mr-2 btn btn-warning btn-sm" title="Ubah" title-pos="up"><i class="icon-feather-edit-2"></i></a>';
            if(request()->user()->can('persediaan.delete'))
                $deleteButton = '<a data-id="' . $data->Id . ' " data-url="/setting/persediaan/' . $data->Id . ' " class="mb-2 mr-2 btn btn-danger btn-sm deleteData" data-title ="Data" title="Hapus" title-pos="up"><i class="icon-feather-trash-2"></i></a>';
            return '<span class="btn-group" role="group">'.$editButton.''.$deleteButton.'</span>';
        })->addColumn('pilih', function ($data) {
            $button = '<button href="#" class="mb-2 mr-2 btn btn-primary pilih-persediaan" data-id="' . $data->Id . '"  data-kd_brg="' . $data->KdBrg . '" data-nm_brg="' . $data->NmBrg . '"  data-nm_brg_lengkap="' . $data->NmBrgLengkap . '" title-pos="up"><svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-share"><path d="M4 12v8a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2v-8"></path><polyline points="16 6 12 2 8 6"></polyline><line x1="12" y1="2" x2="12" y2="15"></line></svg></button>';
            return '<span class="btn-group" role="group">' . $button . '</span>';
        })->rawColumns(['action','pilih'])->make(true);
    }
    public function create()
    {
        $this->authorize('persediaan.create');
        $data = (object) [
            'method' => 'POST',
            'action' => '/setting/persediaan',
            'title' => 'TAMBAH MASTER PERSEDIAAN',
            'persediaan' => []
        ];

        return view('setting.persediaan.form',compact('data'));
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    function inputPersediaan($request, $update = false){
        $inputPersediaan = [
            'KdBrg' => $request->KdBrg,
            'NmBrg' => $request->NmBrg,
            'NmBrgLengkap' => $request->NmBrgLengkap,
            'Qty' => $request->Qty,
        ];
        return $inputPersediaan;

    }
    public function store(Request $request)
    {
        $this->authorize('persediaan.create');
        DB::beginTransaction();
        try {
            $inputPersediaan = $this->inputPersediaan($request);
            $inputPersediaan['Id'] = uuid();
            $inputPersediaan['CreatedAt'] = Carbon::now();
            $inputPersediaan['CreatedBy'] = auth()->user()->NIP;
            $data = Persediaan::create($inputPersediaan);
            DB::commit();
            logActivity('default', ' Master Persediaan')->log("Add Master Persediaan ".$inputPersediaan['Id'] );
            $msg = 'Tambah Master Persediaan Berhasil';
            $type = "success";
        } catch (\Exception $e) {
            DB::rollback();
            $type = "warning";
            $msg = $e->getMessage();
            \Log::error($e->getTraceAsString());
        }

        return redirect(url('setting/persediaan'))->with('flash_message', $msg)
            ->with('flash_type', $type);
    }
    public function edit(Persediaan $persediaan, $show = false)
    {

        $this->authorize('persediaan.read');
        $data = (object) [
            'method' => 'PATCH',
            'action' => "/setting/persediaan/$persediaan->Id",
            'title' => 'EDIT MASTER PERSEDIAAN',
            'persediaan' => $persediaan,
        ];

        return view('setting.persediaan.form',compact('data'));
    }
    public function update(Request $request, Persediaan $persediaan)
    {
        $this->authorize('persediaan.update');
        try {
            DB::beginTransaction();
            $inputPersediaan = $this->inputPersediaan($request, true);
            $inputPersediaan['UpdatedAt'] = Carbon::now();
            $inputPersediaan['UpdatedBy'] = auth()->user()->NIP;
            Persediaan::where('Id', $persediaan->Id)->update($inputPersediaan);
            DB::commit();
            logActivity('default', ' Master Persediaan')->log("Update Master Persediaan ".$persediaan->Id );
            $msg = 'Edit Master Persediaan  Berhasil';
            $type = "success";
        } catch (\Throwable $e) {
            DB::rollback();
            $type = "warning";
            $msg = $e->getMessage();
            \Log::error($e->getTraceAsString());
        }

        return redirect(url('setting/persediaan'))->with('flash_message', $msg)
            ->with('flash_type', $type);
    }
    public function show(Persediaan $persediaan){
        $this->authorize('persediaan.read');
        $data = (object) [
            'serviceCatalog' => $persediaan,
            'persediaan' => Persediaan::whereNull('DeletedAt')->get()
        ];

        return view('setting.persediaan.show',compact('data'));
    }
    public function destroy(Persediaan $persediaan)
    {
        $this->authorize('persediaan.delete');
        $response = self::$response;
        $delete = false;

        try {
            $persediaan->DeletedAt = Carbon::now()->format('Y-m-d H:i:s');
            $persediaan->DeletedBy = auth()->user()->NIP;
            $delete = $persediaan->save();
            $response['success'] = $delete;
            $response['message'] = $delete ? 'Berhasil Hapus Data' : 'Gagal Hapus Data';
            logActivity('default', ' Master Persediaan')->log("Delete Master Persediaan ".$persediaan->Id );

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
        $query = Persediaan::whereNotIn('Id', $tematik)->whereNull('DeletedAt');

        $data = $query->orderBy('Tema')
            ->pluck('Id', 'Tema');

        if ($dataOnly == true)
            return $data;

        $data->value = $request->value ?? '';
        return view('core::_select', compact('data'));
    }
}
