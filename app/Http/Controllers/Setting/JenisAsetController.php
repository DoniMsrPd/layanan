<?php

namespace App\Http\Controllers\Setting;

use App\Http\Controllers\Controller;
use App\Models\Setting\JnsAset;
use Carbon\Carbon;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;

class JenisAsetController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    private static $response = [
		'success' => false,
		'data'    => null,
		'message' => null
	];
    public function index()
    {
        logActivity('default', 'Jenis Aset')->log("View Jenis Aset ");
        return view('setting.jenis-aset.index');
    }
    public function dataTables(Request $request)
    {
        $data = JnsAset::whereNull('DeletedAt');
        return DataTables::of($data)->addColumn('action', function ($data) {
            $editButton = '';
            $deleteButton = '';
            if(request()->user()->can('jenis-aset.update'))
                $editButton = '<a href="' . route('setting.jenis-aset.edit', $data->Id) . '" class="mb-2 mr-2 btn btn-warning btn-sm" title="Ubah" title-pos="up"><i class="icon-feather-edit-2"></i></a>';
            if(request()->user()->can('jenis-aset.delete'))
                $deleteButton = '<a data-id="' . $data->Id . ' " data-url="/setting/jenis-aset/' . $data->Id . ' " class="mb-2 mr-2 btn btn-danger btn-sm deleteData" data-title ="Data" title="Hapus" title-pos="up"><i class="icon-feather-trash-2"></i></a>';
            return '<span class="btn-group" role="group">'.$editButton.''.$deleteButton.'</span>';
        })->rawColumns(['action'])->make(true);
    }
    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        $this->authorize('jenis-aset.create');
        $data = (object) [
            'method' => 'POST',
            'action' => '/setting/jenis-aset',
            'title' => 'TAMBAH JENIS ASET',
            'jenisAset' => []
        ];

        return view('setting.jenis-aset.form',compact('data'));
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $request)
    {

        $this->authorize('jenis-aset.create');
        DB::beginTransaction();
        try {
            $inputJenisAset['Id'] = uuid();
            $inputJenisAset['Nama'] = $request->nama;
            $inputJenisAset['CreatedAt'] = Carbon::now();
            $inputJenisAset['CreatedBy'] = auth()->user()->NIP;
            $data = JnsAset::create($inputJenisAset);
            logActivity('default', 'Jenis Aset')->log("Add Jenis Aset ".$inputJenisAset['Id']);
            DB::commit();
            $msg = 'Tambah Jenis Aset Berhasil';
            $type = "success";
        } catch (\Exception $e) {
            DB::rollback();
            $type = "warning";
            $msg = $e->getMessage();
            \Log::error($e->getTraceAsString());
        }

        return redirect(url('setting/jenis-aset'))->with('flash_message', $msg)
            ->with('flash_type', $type);
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function show(JnsAset $jenisAset){
        $this->authorize('jenis-aset.read');
        $data = (object) [
            'jenisAset' => $jenisAset,
        ];
        logActivity('default', 'Jenis Aset')->log("View Jenis Aset ".$jenisAset->Id);

        return view('setting.jenis-aset.show',compact('data'));
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit(JnsAset $jenisAset, $show = false)
    {
        $this->authorize('jenis-aset.read');
        $data = (object) [
            'method' => 'PATCH',
            'action' => "/setting/jenis-aset/$jenisAset->Id",
            'title' => 'EDIT JENIS ASET',
            'jenisAset' => $jenisAset,
        ];
        return view('setting.jenis-aset.form',compact('data'));
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(Request $request, JnsAset $jenisAset)
    {
        $this->authorize('jenis-aset.update');
        try {
            DB::beginTransaction();
            $inputJenisAset['Nama'] = $request->nama;
            $inputJenisAset['UpdatedAt'] = Carbon::now();
            $inputJenisAset['UpdatedBy'] = auth()->user()->NIP;
            JnsAset::where('Id', $jenisAset->Id)->update($inputJenisAset);
            DB::commit();
            logActivity('default', 'Jenis Aset')->log("Update Jenis Aset ".$jenisAset->Id);
            $msg = 'Edit Jenis Aset Berhasil';
            $type = "success";
        } catch (\Throwable $e) {
            DB::rollback();
            $type = "warning";
            $msg = $e->getMessage();
            \Log::error($e->getTraceAsString());
        }

        return redirect(url('setting/jenis-aset'))->with('flash_message', $msg)
            ->with('flash_type', $type);
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy(JnsAset $jenisAset)
    {

        $this->authorize('jenis-aset.delete');
        $response = self::$response;
        $delete = false;

        try {
            $jenisAset->DeletedAt = Carbon::now()->format('Y-m-d H:i:s');
            $jenisAset->DeletedBy = auth()->user()->NIP;
            $delete = $jenisAset->save();
            $response['success'] = $delete;
            $response['message'] = $delete ? 'Berhasil Hapus Data' : 'Gagal Hapus Data';
            logActivity('default', 'Jenis Aset')->log("Delete Jenis Aset ".$jenisAset->Id);

            return response()->json($response);

        } catch (\Exception $e) {
            $response['success'] = false;
            $response['message'] = $e->getMessage();
            return response()->json($response);
        }
    }
}
