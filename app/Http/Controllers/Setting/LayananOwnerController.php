<?php

namespace App\Http\Controllers\Setting;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Layanan\MstUnitOrgLayananOwner;
use App\Models\Layanan\RefStatusLayanan;
use App\Models\System\MelatiFile;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\Facades\DataTables;

class LayananOwnerController extends Controller
{
    private static $response = [
		'success' => false,
		'data'    => null,
		'message' => null
	];

    public function index(Request $request)
    {
        $this->authorize('master-layanan-owner.read');
        logActivity('default', ' Layanan Owner')->log("View Layanan Owner");
        return view('setting.layanan-owner.index');
    }
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function dataTables()
    {
        $data = MstUnitOrgLayananOwner::all();
        return DataTables::of($data)->EditColumn('PathIcon', function ($data) {
            return $data->PathIcon!=null ?  url('core/'.$data->PathIcon):null;
        })->addColumn('action', function ($data) {
            $editButton = '';
            $deleteButton = '';
            if(request()->user()->can('master-layanan-owner.create'))
                $editButton = '<a href="' . route('setting.layanan-owner.edit', $data->KdUnitOrgOwnerLayanan) . '" class="mb-2 mr-2 btn btn-warning btn-sm" title="Ubah" title-pos="up"><svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-edit-2"><path d="M17 3a2.828 2.828 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5L17 3z"></path></svg></a>';
            // if(request()->user()->can('master-layanan-owner.delete'))
            //     $deleteButton = '<a data-id="' . $data->Id . ' " data-url="/setting/layanan-owner/' . $data->Id . ' " class="mb-2 mr-2 btn btn-danger btn-sm deleteData" data-title ="Data" title="Hapus" title-pos="up"><svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-trash-2"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path><line x1="10" y1="11" x2="10" y2="17"></line><line x1="14" y1="11" x2="14" y2="17"></line></svg></a>';
            return '<span class="btn-group" role="group">'.$editButton.''.$deleteButton.'</span>';
        })->rawColumns(['PathIcon','action'])->make(true);
    }
    public function create()
    {
        $this->authorize('master-layanan-owner.create');
        $data = (object) [
            'method' => 'POST',
            'action' => '/setting/layanan-owner',
            'title' => 'Tambah Master Layanan Owner ',
            'layananOwner' => []
        ];

        return view('setting.layanan-owner.form',compact('data'));
    }
    public function store(Request $request)
    {

        $this->authorize('master-layanan-owner.create');
        DB::beginTransaction();
        try {
            $input['KdUnitOrgOwnerLayanan'] = $request->KdUnitOrgOwnerLayanan;
            $input['NmUnitOrgOwnerLayanan'] = $request->NmUnitOrgOwnerLayanan;
            $input['TglEnd'] = $request->TglEnd;

            if ($request->hasFile('Icon')) {
                $file = $request->file('Icon');
                $fu = sprintf("%s.%s", md5(date('YmdHis')), $file->getClientOriginalExtension());
                Storage::disk('layanan_owner')->put($fu, File::get($file));
                if (Storage::disk('layanan_owner')->put($fu, File::get($file))) {
                    $input['PathIcon'] = 'layanan_owner/storage/' . $fu;
                }
            }
            MstUnitOrgLayananOwner::create($input);
            DB::commit();
            logActivity('default', ' Master Layanan Owner ')->log("Add Master Layanan Owner ".$input['KdUnitOrgOwnerLayanan']);
            $msg = 'Tambah Layanan Owner Berhasil';
            $type = "success";
        } catch (\Exception $e) {
            DB::rollback();
            $type = "warning";
            $msg = $e->getMessage();
            \Log::error($e->getTraceAsString());
        }

        return redirect(url('setting/layanan-owner'))->with('flash_message', $msg)
            ->with('flash_type', $type);
    }
    public function edit(MstUnitOrgLayananOwner $layanan_owner, $show = false)
    {
        $this->authorize('master-layanan-owner.read');
        $data = (object) [
            'method' => 'PATCH',
            'action' => "/setting/layanan-owner/$layanan_owner->KdUnitOrgOwnerLayanan",
            'title' => 'Edit Layanan Owner',
            'layananOwner' => $layanan_owner
        ];
        return view('setting.layanan-owner.form',compact('data'));
    }
    public function update(Request $request, MstUnitOrgLayananOwner $layanan_owner)
    {
        $this->authorize('master-layanan-owner.create');
        DB::beginTransaction();
        try {
            $input['NmUnitOrgOwnerLayanan'] = $request->NmUnitOrgOwnerLayanan;
            $input['TglEnd'] = $request->TglEnd;
            if ($request->hasFile('Icon')) {
                $file = $request->file('Icon');
                $fu = sprintf("%s.%s", md5(date('YmdHis')), $file->getClientOriginalExtension());
                Storage::disk('layanan_owner')->put($fu, File::get($file));
                if (Storage::disk('layanan_owner')->put($fu, File::get($file))) {
                    $input['PathIcon'] = 'layanan_owner/storage/' . $fu;
                    if ($layanan_owner->PathIcon) {
                        $nmFile = explode('/',$layanan_owner->PathIcon)[2];
                        Storage::disk('layanan_owner')->delete($nmFile);
                    }
                }
            }
            MstUnitOrgLayananOwner::where('KdUnitOrgOwnerLayanan', $request->KdUnitOrgOwnerLayanan)->update($input);
            DB::commit();
            logActivity('default', ' Master Layanan Owner ')->log("Update Master Layanan Owner ".$layanan_owner->KdUnitOrgOwnerLayanan);
            $msg = 'Edit Master Layanan Owner  Berhasil';
            $type = "success";
        } catch (\Throwable $e) {
            DB::rollback();
            $type = "warning";
            $msg = $e->getMessage();
            \Log::error($e->getTraceAsString());
        }

        return redirect(url('setting/layanan-owner'))->with('flash_message', $msg)
            ->with('flash_type', $type);
    }
    public function destroy(MstUnitOrgLayananOwner $templatePenyelesaian)
    {
        $this->authorize('master-layanan-owner.delete');
        $response = self::$response;
        $delete = false;

        try {
            $templatePenyelesaian->DeletedAt = Carbon::now()->format('Y-m-d H:i:s');
            $templatePenyelesaian->DeletedBy = auth()->user()->NIP;
            $delete = $templatePenyelesaian->save();
            $response['success'] = $delete;
            $response['message'] = $delete ? 'Berhasil Hapus Data' : 'Gagal Hapus Data';
            logActivity('default', ' Master Layanan Owner ')->log("Delete Master Layanan Owner ".$templatePenyelesaian->Id);

            return response()->json($response);

        } catch (\Exception $e) {
            $response['success'] = false;
            $response['message'] = $e->getMessage();
            return response()->json($response);
        }
    }

    function show(MstUnitOrgLayananOwner $unitOrg)
    {
        $this->authorize('master-layanan-owner.read');
        logActivity('default', ' Layanan Owner')->log("View Layanan Owner Detail");

        return view('setting.layanan-owner._refstatuslayanan', compact('unitOrg'));

    }


    public function dataTablesStatusLayanan($KdUnitOrgOwnerLayanan)
    {
        $this->authorize('master-layanan-owner.read');

        $data = RefStatusLayanan::where('KdUnitOrgOwnerLayanan', $KdUnitOrgOwnerLayanan);

        return DataTables::of($data)
                ->addColumn('action', function ($data) {
                    $editButton = '';
                    $deleteButton = '';
                    if(request()->user()->can('master-layanan-owner.create'))
                        $editButton = '<a href="' . route('setting.layanan-owner.edit', $data->KdUnitOrgOwnerLayanan) . '" class="mb-2 mr-2 btn btn-warning btn-sm" title="Ubah" title-pos="up"><svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-edit-2"><path d="M17 3a2.828 2.828 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5L17 3z"></path></svg></a>';
                    // if(request()->user()->can('master-layanan-owner.delete'))
                    //     $deleteButton = '<a data-id="' . $data->Id . ' " data-url="/setting/layanan-owner/' . $data->Id . ' " class="mb-2 mr-2 btn btn-danger btn-sm deleteData" data-title ="Data" title="Hapus" title-pos="up"><svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-trash-2"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path><line x1="10" y1="11" x2="10" y2="17"></line><line x1="14" y1="11" x2="14" y2="17"></line></svg></a>';
                    return '<span class="btn-group" role="group">'.$editButton.''.$deleteButton.'</span>';
                })->rawColumns(['PathIcon','action'])
                ->make(true);
    }

}
