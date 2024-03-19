<?php

namespace App\Http\Controllers\Setting;

use App\Http\Controllers\Controller;
use App\Models\Setting\Kategori;
use App\Models\Setting\ServiceCatalogTematik;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;

class KategoriController extends Controller
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
        $this->authorize('kategori.read');
        logActivity('default', ' Kategori Layanan')->log("View Kategori Layanan ");
        return view('setting.kategori.index');
    }

    public function dataTables(Request $request)
    {
        $data = Kategori::with('owner')->whereNull('DeletedAt');
        $data->where('KdUnitOrgOwnerLayanan',$request->KdUnitOrgOwnerLayanan ?? kdUnitOrgOwner());
        return DataTables::of($data)->addColumn('action', function ($data) {
            $editButton = '';
            $deleteButton = '';
            if(request()->user()->can('kategori.update'))
                $editButton = '<a href="' . route('setting.kategori.edit', $data->Id) . '" class="mb-2 mr-2 btn btn-warning btn-sm" title="Ubah" title-pos="up">'.btnEdit().'</a>';
            if(request()->user()->can('kategori.delete'))
                $deleteButton = '<a data-id="' . $data->Id . ' " data-url="/setting/kategori/' . $data->Id . ' " class="mb-2 mr-2 btn btn-danger btn-sm deleteData" data-title ="Data" title="Hapus" title-pos="up">'.btnDelete().'</a>';
            return '<span class="btn-group" role="group">'.$editButton.''.$deleteButton.'</span>';
        })->addColumn('pilih', function ($data) {
            $button = '<button href="#" class="mb-2 mr-2 btn btn-primary pilih-kategori" data-id="' . $data->Id . '" data-nama="' . $data->Nama . '" data-keterangan="' . $data->Keterangan . '"  title-pos="up"><svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-share"><path d="M4 12v8a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2v-8"></path><polyline points="16 6 12 2 8 6"></polyline><line x1="12" y1="2" x2="12" y2="15"></line></svg></button>';
            return '<span class="btn-group" role="group">' . $button . '</span>';
        })->addColumn('mobile', function ($data) {

            $button = '<button href="#" class="btn btn-sm btn-primary waves-effect waves-light pilih-kategori" data-id="' . $data->Id . '" data-nama="' . $data->Nama . '" data-keterangan="' . $data->Keterangan . '"  title-pos="up"><svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-share"><path d="M4 12v8a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2v-8"></path><polyline points="16 6 12 2 8 6"></polyline><line x1="12" y1="2" x2="12" y2="15"></line></svg></button>';
            $button = '<span class="btn-group" role="group">' . $button . '</span>';
            $table = "$data->Nama <br> $data->Keterangan <br> $button";
            return $table;
        })->rawColumns(['action','pilih','mobile'])->make(true);
    }
    public function create()
    {
        $this->authorize('kategori.create');
        $data = (object) [
            'method' => 'POST',
            'action' => '/setting/kategori',
            'title' => 'Tambah Kategori Layanan',
            'kategori' => []
        ];

        return view('setting.kategori.form',compact('data'));
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    function inputKategori($request, $update = false){
        $inputKategori = [
            'Nama' => $request->Nama,
            'Keterangan' => $request->Keterangan,
            'TglStart' => $request->tglStart,
            'TglEnd' => $request->tglEnd,
        ];
        return $inputKategori;

    }
    public function store(Request $request)
    {
        $this->authorize('kategori.create');
        DB::beginTransaction();
        try {
            $inputKategori = $this->inputKategori($request);
            $inputKategori['Id'] = uuid();
            $inputKategori['CreatedAt'] = Carbon::now();
            $inputKategori['CreatedBy'] = auth()->user()->NIP;
            $inputKategori['KdUnitOrgOwnerLayanan'] = $request->KdUnitOrgOwnerLayanan;
            $data = Kategori::create($inputKategori);
            DB::commit();
            logActivity('default', ' Kategori Layanan')->log("Add Kategori Layanan ".$inputKategori['Id'] );
            $msg = 'Tambah Kategori Layanan Berhasil';
            $type = "success";
        } catch (\Exception $e) {
            DB::rollback();
            $type = "warning";
            $msg = $e->getMessage();
            \Log::error($e->getTraceAsString());
        }

        return redirect(url('setting/kategori'))->with('flash_message', $msg)
            ->with('flash_type', $type);
    }
    public function edit(Kategori $kategori, $show = false)
    {

        $this->authorize('kategori.read');
        $data = (object) [
            'method' => 'PATCH',
            'action' => "/setting/kategori/$kategori->Id",
            'title' => 'Edit Kategori Layanan',
            'kategori' => $kategori,
        ];

        return view('setting.kategori.form',compact('data'));
    }
    public function update(Request $request, Kategori $kategori)
    {
        $this->authorize('kategori.update');
        try {
            DB::beginTransaction();
            $inputKategori = $this->inputKategori($request, true);
            $inputKategori['UpdatedAt'] = Carbon::now();
            $inputKategori['UpdatedBy'] = auth()->user()->NIP;
            Kategori::where('Id', $kategori->Id)->update($inputKategori);
            DB::commit();
            logActivity('default', ' Kategori Layanan')->log("Update Kategori Layanan ".$kategori->Id );
            $msg = 'Edit Kategori Layanan  Berhasil';
            $type = "success";
        } catch (\Throwable $e) {
            DB::rollback();
            $type = "warning";
            $msg = $e->getMessage();
            \Log::error($e->getTraceAsString());
        }

        return redirect(url('setting/kategori'))->with('flash_message', $msg)
            ->with('flash_type', $type);
    }
    public function destroy(Kategori $kategori)
    {
        $this->authorize('kategori.delete');
        $response = self::$response;
        $delete = false;

        try {
            $kategori->DeletedAt = Carbon::now()->format('Y-m-d H:i:s');
            $kategori->DeletedBy = auth()->user()->NIP;
            $delete = $kategori->save();
            $response['success'] = $delete;
            $response['message'] = $delete ? 'Berhasil Hapus Data' : 'Gagal Hapus Data';
            logActivity('default', ' Kategori Layanan')->log("Delete Kategori Layanan ".$kategori->Id );

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
        $query = Kategori::whereNotIn('Id', $tematik)->whereNull('DeletedAt');

        $data = $query->orderBy('Tema')
            ->pluck('Id', 'Tema');

        if ($dataOnly == true)
            return $data;

        $data->value = $request->value ?? '';
        return view('core::_select', compact('data'));
    }
}
