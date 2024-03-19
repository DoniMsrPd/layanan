<?php

namespace App\Http\Controllers\Setting;

use App\Http\Controllers\Controller;
use App\Models\Setting\Solver;
use App\Models\System\Pegawai;
use App\Models\User;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class SolverController extends Controller
{

    private static $response = [
        'success' => false,
        'data'    => null,
        'message' => null
    ];

    public function dataTables($api=false)
    {
        $data = Solver::with(['pegawai']);
        if(request()->mstGroupSolverId){
            $data->where('MstGroupSolverId',request()->mstGroupSolverId);
        }
        if(request()->groupSolver){
            $groupSolver = explode(',', request()->groupSolver);
            $solver = explode(',', request()->solver);
            $solverNip = $data->whereIn('MstGroupSolverId',$groupSolver)->pluck('Nip')->toArray();
            if(request()->excludeGroup==1){
                $data = Pegawai::filtered()->where('stsPensiun', '0')->whereNotIn('Nip',$solver);
            } else {
                $data = Pegawai::filtered()->where('stsPensiun', '0')->whereIn('Nip',$solverNip);
            }

        }
        if ($api) {
            return $data;
        }
        return DataTables::of($data)->addColumn('action', function ($data) {
            $deleteButton = '';
            if(request()->user()->can('solver.delete'))
                $deleteButton= '<a data-id="' . $data->Id . ' " data-url="/setting/solver/' . $data->Id . ' " class="mb-2 mr-2 btn btn-danger btn-sm deleteData" data-title ="Data" title="Hapus" title-pos="up">'.btnDelete().'</a>';
            return '<span class="btn-group" role="group"> '.$deleteButton.'</span>';
        })->addColumn('pilih', function ($data) {
            $NmPeg = $data->NmPeg ??$data->pegawai->NmPeg;
            $button = '<button href="#" class="mb-2 mr-2 btn btn-primary pilih-solver" data-nip="' . $data->Nip . '"  data-nama="' . $NmPeg . '"  title-pos="up"><svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-share"><path d="M4 12v8a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2v-8"></path><polyline points="16 6 12 2 8 6"></polyline><line x1="12" y1="2" x2="12" y2="15"></line></svg></button>';
            return '<span class="btn-group" role="group">' . $button . '</span>';
        })->addColumn('NmPeg',function($data){
            return $data->NmPeg??$data->pegawai->NmPeg;
        })->addColumn('NmUnitOrg',function($data){
            return $data->NmUnitOrg??$data->pegawai->NmUnitOrg;
        })->addColumn('NmUnitOrgInduk',function($data){
            return $data->NmUnitOrgInduk??$data->pegawai->NmUnitOrgInduk;
        })->addColumn('mobile', function ($data) {
            $NmPeg = $data->NmPeg ??$data->pegawai->NmPeg;
            $button = '<button href="#" class="btn-sm btn btn-primary pilih-solver" data-nip="' . $data->Nip . '"  data-nama="' . $NmPeg . '"  title-pos="up"><svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-share"><path d="M4 12v8a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2v-8"></path><polyline points="16 6 12 2 8 6"></polyline><line x1="12" y1="2" x2="12" y2="15"></line></svg></button>';
            $button = '<span class="btn-group" role="group">' . $button . '</span>';
            return ($data->NmPeg??$data->pegawai->NmPeg)."<br>".($data->NmUnitOrg??$data->pegawai->NmUnitOrg)."<br>".($data->NmUnitOrgInduk??$data->pegawai->NmUnitOrgInduk)."<br>".$button;
        })->rawColumns(['action','pilih','NmPeg','NmUnitOrg','NmUnitOrgInduk','mobile'])
        ->setRowId('Nip')->make(true);
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->authorize('solver.create');
        try {
            $InputSolver['Id'] = uuid();
            $InputSolver['MstGroupSolverId'] = $request->mstGroupSolverId;
            $InputSolver['Nip'] = $request->nip;
            // $InputSolver['CreatedAt'] = Carbon::now();
            // $InputSolver['InsertedBy'] = auth()->user()->NIP;
            $data = Solver::create($InputSolver);
            $user = User::where('NIP',$request->nip)->first();
            $user->assignRole('Solver');

            $response['success'] =$data;
            $response['message'] =$data ? 'Berhasil Tambah Solver' : 'Gagal Tambah Solver';

            logActivity('default', ' Group Solver')->log("Update Solver ".$InputSolver['Id']);
            return response()->json($response);
        } catch (\Exception $e) {
            $response['success'] = false;
            $response['message'] = $e->getMessage();
            return response()->json($response);
        }
    }
    public function destroy(Solver $solver)
    {
        $this->authorize('solver.delete');
        $response = self::$response;
        $delete = false;
        try {
            $delete = $solver->delete();
            $response['success'] = $delete;
            $response['message'] = $delete ? 'Berhasil Hapus Solver' : 'Gagal Hapus Solver';

            logActivity('default', ' Group Solver')->log("Update Solver ".$solver->Id);
            return response()->json($response);
        } catch (\Exception $e) {
            $response['success'] = false;
            $response['message'] = $e->getMessage();
            return response()->json($response);
        }
    }
}
