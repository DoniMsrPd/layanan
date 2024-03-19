<?php

namespace App\Http\Controllers\Setting;

use App\Http\Controllers\Controller;
use App\Models\Setting\JnsAset;
use App\Models\Setting\TypeAset;
use Carbon\Carbon;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;

class TypeAsetController extends Controller
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
    public function dataTables(Request $request)
    {
        $data = TypeAset::with('jnsAset')->whereNull('RefTypeAset.DeletedAt')->where(function ($q) {
            $q->where('RefJnsAsetId', request()->jenisAsetId)->orWhere('RefJnsAsetIdInc', request()->jenisAsetIdInc);
        });;
        return DataTables::of($data)->addColumn('action', function ($data) {
            $editButton = '';
            $deleteButton = '';
            if(request()->user()->can('type-aset.update'))
                $editButton = '<a href="' . route('setting.type-aset.edit', $data->Id) . '" class="mb-2 mr-2 btn btn-warning btn-sm" title="Ubah" title-pos="up"><i class="icon-feather-edit-2"></i></a>';
            if(request()->user()->can('type-aset.delete'))
                $deleteButton = '<a data-id="' . $data->Id . ' " data-url="/setting/type-aset/' . $data->Id . ' " class="mb-2 mr-2 btn btn-danger btn-sm deleteData" data-title ="Data" title="Hapus" title-pos="up"><i class="icon-feather-trash-2"></i></a>';
            return '<span class="btn-group" role="group">'.$editButton.''.$deleteButton.'</span>';
        })->rawColumns(['action'])->make(true);
    }
    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create(JnsAset $jenisAset)
    {
        $this->authorize('type-aset.create');
        $data = (object) [
            'method' => 'POST',
            'action' => '/setting/type-aset',
            'title' => 'TAMBAH TYPE ASET',
            'jenisAset' => $jenisAset,
            'typeAset' => []
        ];

        return view('setting.type-aset.form',compact('data'));
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $request)
    {
        $this->authorize('type-aset.create');
        DB::beginTransaction();
        try {
            $inputTypeAset['Id'] = uuid();
            $inputTypeAset['Nama'] = $request->nama;
            $inputTypeAset['RefJnsAsetId'] = $request->jenisAsetId;
            $inputTypeAset['CreatedAt'] = Carbon::now();
            $inputTypeAset['CreatedBy'] = auth()->user()->NIP;
            $data = TypeAset::create($inputTypeAset);
            DB::commit();
            logActivity('default', 'Jenis Aset')->log("Add Type Aset ".$inputTypeAset['Id'] );
            $msg = 'Tambah Type Aset Berhasil';
            $type = "success";
        } catch (\Exception $e) {
            DB::rollback();
            $type = "warning";
            $msg = $e->getMessage();
            \Log::error($e->getTraceAsString());
        }

        return redirect(url('/setting/jenis-aset/'.$request->jenisAsetId))->with('flash_message', $msg)
            ->with('flash_type', $type);
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function show($id)
    {
        return view('setting.type-aset.show');
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit(TypeAset $typeAset, $show = false)
    {
        $this->authorize('type-aset.read');
        $data = (object) [
            'method' => 'PATCH',
            'action' => "/setting/type-aset/$typeAset->Id",
            'title' => 'EDIT TYPE ASET',
            'typeAset' => $typeAset,
            'jenisAset' => $typeAset->jnsAset,
        ];
        return view('setting.type-aset.form',compact('data'));
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(Request $request, TypeAset $typeAset)
    {
        $this->authorize('type-aset.update');
        try {
            DB::beginTransaction();
            $inputTypeAset['Nama'] = $request->nama;
            $inputTypeAset['UpdatedAt'] = Carbon::now();
            $inputTypeAset['UpdatedBy'] = auth()->user()->NIP;
            TypeAset::where('Id', $typeAset->Id)->update($inputTypeAset);
            DB::commit();
            logActivity('default', 'Jenis Aset')->log("Update Type Aset ".$typeAset->Id );
            $msg = 'Edit Jenis Aset Berhasil';
            $type = "success";
        } catch (\Throwable $e) {
            DB::rollback();
            $type = "warning";
            $msg = $e->getMessage();
            \Log::error($e->getTraceAsString());
        }

        return redirect(url('/setting/jenis-aset/'.$request->jenisAsetId))->with('flash_message', $msg)
            ->with('flash_type', $type);
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy(TypeAset $typeAset)
    {
        $this->authorize('type-aset.delete');
        $response = self::$response;
        $delete = false;

        try {
            $typeAset->DeletedAt = Carbon::now()->format('Y-m-d H:i:s');
            $typeAset->DeletedBy = auth()->user()->NIP;
            $delete = $typeAset->save();
            $response['success'] = $delete;
            $response['message'] = $delete ? 'Berhasil Hapus Data' : 'Gagal Hapus Data';
            logActivity('default', 'Jenis Aset')->log("Delete Type Aset ".$typeAset->Id );

            return response()->json($response);

        } catch (\Exception $e) {
            $response['success'] = false;
            $response['message'] = $e->getMessage();
            return response()->json($response);
        }
    }
    public function select(Request $request, $dataOnly = false)
    {
        $query = TypeAset::where('RefJnsAsetId', $request->RefJnsAsetId);

        $data = $query->orderBy('Nama')
            ->pluck('Id', 'Nama');

        if ($dataOnly == true)
            return $data;

        $data->value = $request->value ?? '';
        return view('core::_select', compact('data'));
    }
}
