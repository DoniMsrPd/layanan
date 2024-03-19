<?php

namespace App\Http\Controllers\Setting;

use App\Http\Controllers\Controller;
use App\Models\Setting\GroupSolver;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;

class GroupSolverController extends Controller
{
    private static $response = [
        'success' => false,
        'data'    => null,
        'message' => null
    ];
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        logActivity('default', ' Group Solver')->log("View Group Solver ");
        return view('setting.group-solver.index');
    }
    public function dataTables(Request $request)
    {
        $data = GroupSolver::with('owner')->where('KdUnitOrgOwnerLayanan',kdUnitOrgOwner())->get();
        return DataTables::of($data)->addColumn('action', function ($data) {
            $deleteButton = '';
            $editButton = '';
            if(request()->user()->can('group-solver.update'))
                $editButton = '<a href="' . route('setting.group-solver.edit', $data->Id) . '" class="mb-2 mr-2 btn btn-warning btn-sm" title="Ubah" title-pos="up">'.btnEdit().'</a>';
            if (request()->user()->can('group-solver.delete'))
                $deleteButton = '<a data-id="' . $data->Id . ' " data-url="/setting/group-solver/' . $data->Id . ' " class="mb-2 mr-2 btn btn-danger btn-sm deleteData" data-title ="Data" title="Hapus" title-pos="up">'.btnDelete().'</a>';
            return '<span class="btn-group" role="group"> ' .$editButton. $deleteButton . '</span>';
        })->addColumn('pilih', function ($data) {
            $button = '<button href="#" class="mb-2 mr-2 btn btn-primary pilih-group-solver" data-id="' . $data->Id . '" data-nama="' . $data->Nama . '"  title-pos="up"><svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-share"><path d="M4 12v8a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2v-8"></path><polyline points="16 6 12 2 8 6"></polyline><line x1="12" y1="2" x2="12" y2="15"></line></svg></button>';
            return '<span class="btn-group" role="group">' . $button . '</span>';
        })->addColumn('mobile', function ($data) {
            $button = '<button href="#" class="btn-sm mb-2 mr-2 btn btn-primary pilih-group-solver" data-id="' . $data->Id . '" data-nama="' . $data->Nama . '"  title-pos="up"><svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-share"><path d="M4 12v8a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2v-8"></path><polyline points="16 6 12 2 8 6"></polyline><line x1="12" y1="2" x2="12" y2="15"></line></svg></button>';
            $button = '<span class="btn-group" role="group">' . $button . '</span>';

            return  "$data->Kode <br> $data->Nama <br> $button";
        })->setRowId('Id')->rawColumns(['action', 'pilih','mobile'])->make(true);
    }
    public function show(GroupSolver $groupSolver)
    {
        $this->authorize('group-solver.read');
        $data = (object) [
            'groupSolver' => $groupSolver,
        ];

        logActivity('default', ' Group Solver')->log("View Group Solver ".$groupSolver->Id);
        return view('setting.group-solver.show', compact('data'));
    }
    public function create()
    {
        $this->authorize('group-solver.create');
        $data = (object) [
            'method' => 'POST',
            'action' => '/setting/group-solver',
            'title' => 'TAMBAH GROUP SOLVER',
            'groupSolver' => [],
            'unitOrgSelected' => GroupSolver::pluck('Id')->implode(',')
        ];
        return view('setting.group-solver.form', compact('data'));
    }
    public function store(Request $request)
    {
        $this->authorize('group-solver.create');
        try {
            $InputGroupSolver['Id'] = $request->Id;
            $InputGroupSolver['Nama'] = $request->Nama;
            $InputGroupSolver['Kode'] = $request->Kode;
            $InputGroupSolver['KdUnitOrgOwnerLayanan'] = $request->KdUnitOrgOwnerLayanan;
            $data = GroupSolver::create($InputGroupSolver);
            DB::commit();
            logActivity('default', ' Group Solver')->log("Add Group Solver ".$InputGroupSolver['Id']);
            $msg = 'Tambah Group Solver Berhasil';
            $type = "success";
        } catch (\Exception $e) {
            DB::rollback();
            $type = "warning";
            $msg = $e->getMessage();
            \Log::error($e->getTraceAsString());
        }
        return redirect(url('setting/group-solver'))->with('flash_message', $msg)
            ->with('flash_type', $type);
    }
    public function edit(GroupSolver $groupSolver, $show = false)
    {
        $this->authorize('group-solver.read');
        $data = (object) [
            'method' => 'PATCH',
            'action' => "/setting/group-solver/$groupSolver->Id",
            'title' => 'EDIT GROUP SOLVER',
            'groupSolver' => $groupSolver,
            'unitOrgSelected' => GroupSolver::pluck('Id')->implode(',')
        ];
        return view('setting.group-solver.form',compact('data'));
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(Request $request, GroupSolver $groupSolver)
    {
        $this->authorize('group-solver.update');
        try {
            DB::beginTransaction();
            $InputGroupSolver['Id'] = $request->Id;
            $InputGroupSolver['Nama'] = $request->Nama;
            $InputGroupSolver['Kode'] = $request->Kode;
            GroupSolver::where('Id', $groupSolver->Id)->update($InputGroupSolver);
            DB::commit();
            logActivity('default', ' Group Solver')->log("Update Group Solver ".$groupSolver->Id);
            $msg = 'Edit Group Solver Berhasil';
            $type = "success";
        } catch (\Throwable $e) {
            DB::rollback();
            $type = "warning";
            $msg = $e->getMessage();
            \Log::error($e->getTraceAsString());
        }

        return redirect(url('setting/group-solver'))->with('flash_message', $msg)
            ->with('flash_type', $type);
    }
    public function destroy(GroupSolver $groupSolver)
    {
        $this->authorize('group-solver.delete');
        $response = self::$response;
        $delete = false;
        try {
            $delete = $groupSolver->delete();
            $response['success'] = $delete;
            $response['message'] = $delete ? 'Berhasil Hapus Group Solver' : 'Gagal Hapus Group Solver';
            logActivity('default', ' Group Solver')->log("Delete Group Solver ".$groupSolver->Id);

            return response()->json($response);
        } catch (\Exception $e) {
            $response['success'] = false;
            $response['message'] = $e->getMessage();
            return response()->json($response);
        }
    }
}
