<?php

namespace App\Http\Controllers\Setting;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Setting\TemplatePenyelesaian;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class TemplatePenyelesaianController extends Controller
{
    private static $response = [
		'success' => false,
		'data'    => null,
		'message' => null
	];
    public function index(Request $request)
    {
        $this->authorize('template-penyelesaian.read');
        logActivity('default', ' Template Penyelesaian')->log("View Template Penyelesaian");
        return view('setting.template-penyelesaian.index');
    }
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function dataTables()
    {
        $data = TemplatePenyelesaian::whereNull('DeletedAt')->where('KdUnitOrgOwnerLayanan',kdUnitOrgOwner());
        return DataTables::of($data)->addColumn('pilih', function ($data) {
            $button = '<button href="#" class="mb-2 mr-2 btn btn-primary btn-sm pilih-template" data-template="' . $data->Template . '"  title-pos="up"><svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-share"><path d="M4 12v8a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2v-8"></path><polyline points="16 6 12 2 8 6"></polyline><line x1="12" y1="2" x2="12" y2="15"></line></svg></button>';
            return '<span class="btn-group" role="group">' . $button . '</span>';
        })->addColumn('mobile', function ($data) {
            $button = '<button href="#" class="mb-2 mr-2 btn btn-primary btn-sm pilih-template" data-template="' . $data->Template . '"  title-pos="up"><svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-share"><path d="M4 12v8a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2v-8"></path><polyline points="16 6 12 2 8 6"></polyline><line x1="12" y1="2" x2="12" y2="15"></line></svg></button>';
            $button =  '<span class="btn-group" role="group">' . $button . '</span>';
            return "<b>$data->Nama</b>"."<br>".$data->Template."<br>".$button;
        })->editColumn('Template',function($data){
            return $data->Template;
        })->addColumn('action', function ($data) {
            $editButton = '';
            $deleteButton = '';
            if(request()->user()->can('template-penyelesaian.update'))
                $editButton = '<a href="' . route('setting.template-penyelesaian.edit', $data->Id) . '" class="mb-2 mr-2 btn btn-warning btn-sm" title="Ubah" title-pos="up"><svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-edit-2"><path d="M17 3a2.828 2.828 0 1 1 4 4L7.5 20.5 2 22l1.5-5.5L17 3z"></path></svg></a>';
            if(request()->user()->can('template-penyelesaian.delete'))
                $deleteButton = '<a data-id="' . $data->Id . ' " data-url="/setting/template-penyelesaian/' . $data->Id . ' " class="mb-2 mr-2 btn btn-danger btn-sm deleteData" data-title ="Data" title="Hapus" title-pos="up"><svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-trash-2"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path><line x1="10" y1="11" x2="10" y2="17"></line><line x1="14" y1="11" x2="14" y2="17"></line></svg></a>';
            return '<span class="btn-group" role="group">'.$editButton.''.$deleteButton.'</span>';
        })->rawColumns(['pilih','action','Template','mobile'])->make(true);
    }
    public function create()
    {
        $this->authorize('template-penyelesaian.create');
        $data = (object) [
            'method' => 'POST',
            'action' => '/setting/template-penyelesaian',
            'title' => 'Tambah Master Template Penyelesaian ',
            'templatePenyelesaian' => []
        ];

        return view('setting.template-penyelesaian.form',compact('data'));
    }
    public function store(Request $request)
    {

        $this->authorize('template-penyelesaian.create');
        DB::beginTransaction();
        try {
            $inputTemplate['Id'] = uuid();
            $inputTemplate['Nama'] = $request->Nama;
            $inputTemplate['KdUnitOrgOwnerLayanan'] = kdUnitOrgOwner();
            $inputTemplate['Template'] = $request->Template;
            $inputTemplate['CreatedAt'] = Carbon::now();
            $inputTemplate['CreatedBy'] = auth()->user()->NIP;
            $data = TemplatePenyelesaian::create($inputTemplate);
            DB::commit();
            logActivity('default', ' Master Template Penyelesaian ')->log("Add Master Template Penyelesaian ".$inputTemplate['Id']);
            $msg = 'Tambah Template Penyelesaian Berhasil';
            $type = "success";
        } catch (\Exception $e) {
            DB::rollback();
            $type = "warning";
            $msg = $e->getMessage();
            \Log::error($e->getTraceAsString());
        }

        return redirect(url('setting/template-penyelesaian'))->with('flash_message', $msg)
            ->with('flash_type', $type);
    }
    public function edit(TemplatePenyelesaian $templatePenyelesaian, $show = false)
    {

        $this->authorize('template-penyelesaian.read');
        $data = (object) [
            'method' => 'PATCH',
            'action' => "/setting/template-penyelesaian/$templatePenyelesaian->Id",
            'title' => 'Edit Template Penyelesaian',
            'templatePenyelesaian' => $templatePenyelesaian
        ];
        return view('setting.template-penyelesaian.form',compact('data'));
    }
    public function update(Request $request, TemplatePenyelesaian $templatePenyelesaian)
    {
        $this->authorize('template-penyelesaian.update');
        DB::beginTransaction();
        try {
            $inputTemplate['Nama'] = $request->Nama;
            $inputTemplate['Template'] = $request->Template;
            $inputTemplate['UpdatedAt'] = Carbon::now();
            $inputTemplate['UpdatedBy'] = auth()->user()->NIP;
            TemplatePenyelesaian::where('Id', $request->id)->update($inputTemplate);
            DB::commit();
            logActivity('default', ' Master Template Penyelesaian ')->log("Update Master Template Penyelesaian ".$templatePenyelesaian->Id);
            $msg = 'Edit Master Template Penyelesaian  Berhasil';
            $type = "success";
        } catch (\Throwable $e) {
            DB::rollback();
            $type = "warning";
            $msg = $e->getMessage();
            \Log::error($e->getTraceAsString());
        }

        return redirect(url('setting/template-penyelesaian'))->with('flash_message', $msg)
            ->with('flash_type', $type);
    }
    public function destroy(TemplatePenyelesaian $templatePenyelesaian)
    {
        $this->authorize('template-penyelesaian.delete');
        $response = self::$response;
        $delete = false;

        try {
            $templatePenyelesaian->DeletedAt = Carbon::now()->format('Y-m-d H:i:s');
            $templatePenyelesaian->DeletedBy = auth()->user()->NIP;
            $delete = $templatePenyelesaian->save();
            $response['success'] = $delete;
            $response['message'] = $delete ? 'Berhasil Hapus Data' : 'Gagal Hapus Data';
            logActivity('default', ' Master Template Penyelesaian ')->log("Delete Master Template Penyelesaian ".$templatePenyelesaian->Id);

            return response()->json($response);

        } catch (\Exception $e) {
            $response['success'] = false;
            $response['message'] = $e->getMessage();
            return response()->json($response);
        }
    }
}