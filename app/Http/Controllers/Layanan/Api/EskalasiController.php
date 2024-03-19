<?php

namespace App\Http\Controllers\Layanan\Api;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Layanan\EskalasiController as LayananEskalasiController;
use App\Http\Controllers\Setting\SolverController;
use App\Http\Resources\LayananGroupSolverResource;
use App\Http\Resources\LayananSolverResource;
use App\Http\Resources\PaginatedCollection;
use App\Http\Resources\SolverResource;
use App\Models\Layanan\LayananGroupSolver;
use App\Models\Layanan\LayananSolver;
use App\Models\Setting\GroupSolver;
use App\Services\LayananService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class EskalasiController extends Controller
{

    protected $ctr;
    protected $solverCtr;

    public function __construct()
    {
        $this->ctr = app(LayananEskalasiController::class);
        $this->solverCtr = app(SolverController::class);
    }
    function getGroupSolver($layananId) {
        $data = LayananGroupSolver::where('LayananId', $layananId)->whereNull('DeletedAt')->get();
        return response(['success' => true, "data" => LayananGroupSolverResource::collection($data) ], Response::HTTP_OK);
    }
    function showGroupSolver() {
        $data = GroupSolver::paginate(10);
        return response(['success' => true, "data" => $data ], Response::HTTP_OK);
    }
    function storeGroupSolver(Request $request, LayananService $layananService) {
        return response()->json($this->ctr->storeGroupSolver($request,$layananService,true), Response::HTTP_OK);
    }

    function destroyGroupSolver($id) {
        return $this->ctr->destroyGroupSolver($id);
    }
    function getSolver($layananId) {
        $solver = LayananSolver::selectRaw('LayananSolver.Id,NmPeg,LayananSolver.Nip,Catatan')->where('LayananId', $layananId)->join('SpgDataCurrent', 'SpgDataCurrent.Nip', '=', 'LayananSolver.Nip')->orderBy('NmPeg','ASC')->whereNull('DeletedAt')->get();
        return response(['success' => true,"data" => LayananSolverResource::collection($solver) ], Response::HTTP_OK);
    }
    function showSolver() {
        $data = $this->solverCtr->dataTables(true);
        return response(['success' => true, "data" => new PaginatedCollection($data->paginate(10),SolverResource::class) ], Response::HTTP_OK);
    }
    function storeSolver(Request $request, LayananService $layananService) {
        return response()->json($this->ctr->storeSolver($request,$layananService,true), Response::HTTP_OK);
    }
    function destroySolver($id) {
        return $this->ctr->destroySolver($id);
    }
    function updateSolver(Request $request,$id) {
        return $this->ctr->updateSolver($request,$id);
    }
}