<?php

namespace App\Http\Controllers\System;

use App\Http\Controllers\Controller;
use App\Models\Layanan\LayananSolver;
use App\Models\Layanan\MstUnitOrgLayananOwner;
use App\Models\Layanan\RefStatusLayanan;
use App\Models\Setting\Solver;
use App\Services\LayananService;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class DashboardController extends Controller
{
    private $layananService;
    public function __construct(LayananService $layananService)
    {
        $this->layananService = $layananService;
    }
    public function dashboardEcommerce()
    {
        $pageConfigs = ['pageHeader' => false];

        if (pegawaiBiasa()) {
            $data = $this->dataPegawai();
            return view('system.dashboard.dashboard-pegawai', compact('pageConfigs', 'data'));
        }
        $data = (object) [
            'tickets' => $this->layananService->getAllLayanan([], false)->selectRaw("No,StatusLayanan,CASE
            WHEN
                [StatusLayanan] = 1 THEN
            'New Ticket'
            WHEN
                [StatusLayanan] = 2 THEN
            'Open Ticket'
            WHEN
                [StatusLayanan] = 3 THEN
            'In Progress Ticket'
            WHEN
                [StatusLayanan] = 4 THEN
            'Solved Ticket'
            WHEN
                [StatusLayanan] = 5 THEN
            'Closed Ticket'
            WHEN
                [StatusLayanan] = 6 THEN
            'Confirm'
            END Name,
            COUNT ( Layanan.Id ) Jumlah ")->whereIn('StatusLayanan', [1, 2, 3, 4, 5, 6])
                ->leftJoin('RefStatusLayanan', function ($join) {
                    $join->on('RefStatusLayanan.Id', '=', 'Layanan.StatusLayanan');
                    $join->on('RefStatusLayanan.KdUnitOrgOwnerLayanan', '=', 'Layanan.KdUnitOrgOwnerLayanan');
                })
                ->where('RefStatusLayanan.KdUnitOrgOwnerLayanan', kdUnitOrgOwner())
                ->whereBetween('Layanan.CreatedAt', [date('Y') . '-01-01 00:00:00', date('Y-m-d') . ' 23:59:59'])->groupBy('No', 'StatusLayanan')->orderBy('No')->orderBy('StatusLayanan')->get(),
            'statusLayanan' => RefStatusLayanan::where('RefStatusLayanan.KdUnitOrgOwnerLayanan', kdUnitOrgOwner())->get()
        ];
        return view('system.dashboard.dashboard-ecommerce', compact('pageConfigs', 'data'));
    }

    private function dataPegawai()
    {
        if (request()->tglStart) {
            $q = [request()->tglStart . ' 00:00:00', request()->tglEnd . ' 23:59:59'];
        } else {
            $q = [date('Y') . '-01-01 00:00:00', date('Y-m-d') . ' 23:59:59'];

        }
        return (object) [
            'MstUnitOrgLayananOwner' => MstUnitOrgLayananOwner::whereNull('KdKantor')->orWhereRaw("KdKantor =[dbo].[Func_getKdKantorLayanan](?)",[userNip()])->get(),
            'tickets' => $this->layananService->getAllLayanan([], false)->selectRaw("No,StatusLayanan,
        Layanan.KdUnitOrgOwnerLayanan,Nama as Name,
        COUNT ( Layanan.Id ) Jumlah ")->whereIn('StatusLayanan', [1, 2, 3, 4, 5, 6, 7])
                ->leftJoin('RefStatusLayanan', function ($join) {
                    $join->on('RefStatusLayanan.Id', '=', 'Layanan.StatusLayanan');
                    $join->on('RefStatusLayanan.KdUnitOrgOwnerLayanan', '=', 'Layanan.KdUnitOrgOwnerLayanan');
                })
                ->whereBetween('Layanan.CreatedAt', $q)->groupBy('No', 'StatusLayanan', 'Layanan.KdUnitOrgOwnerLayanan', 'Nama')->orderBy('No')->orderBy('StatusLayanan')->get(),
        ];
    }
    public function datatablesLayanan(Request $request)
    {

        $data = $this->layananService->getAllLayanan([], false)->where('Layanan.KdUnitOrgOwnerLayanan', kdUnitOrgOwner())->selectRaw("ServiceCatalogKode, ServiceCatalogNama, COUNT(Id) Jumlah")->whereBetween('Layanan.CreatedAt', [$request->tglStart ? $request->tglStart . ' 00:00:00' : date('Y') . '-01-01' . ' 00:00:00', $request->tglEnd ? $request->tglEnd . ' 23:59:59' : date('Y-m-d') . ' 23:59:59'])->groupByRaw("ServiceCatalogKode, ServiceCatalogNama");
        if ($request->statusLayanan && $request->statusLayanan != 'null') {
            $statusLayanan = explode(",", $request->statusLayanan);
            $data->whereIn('StatusLayanan', $statusLayanan);
        }
        return DataTables::of($data)->order(function ($query) {
            $query->orderBy('ServiceCatalogKode');
        })->setRowId(function ($data) {
            return $data->ServiceCatalogKode;
        })->make(true);
    }

    public function dashboardData(Request $request)
    {
        if (pegawaiBiasa()) {
            $data = $this->dataPegawai();
            return view('system.dashboard.ticket-pegawai', compact('data'))->render();
        }

        $isOther = kdUnitOrgOwner() != '100205000000';
        if ($isOther) {
            if (request()->user()->hasAllRoles(['Solver', 'Admin Probis Layanan']) || request()->user()->hasAllRoles(['Solver', 'Admin Proses Bisnis'])) {
                $layananName = [1, 2, 3, 6, 7, 4, 5];
            } elseif (request()->user()->hasAllRoles(['Solver', 'Pejabat Struktural'])) {
                $layananName = [2, 3, 6, 7, 4, 5];
            } elseif (request()->user()->hasAllRoles(['Solver', 'Operator'])) {
                $layananName = [1, 2, 3, 6, 7, 4, 5];
            } elseif (request()->user()->hasAllRoles(['Solver'])) {
                $layananName = [2, 3, 6, 7, 4, 5];
            }
            $refStatusLayanan = RefStatusLayanan::where('KdUnitOrgOwnerLayanan', kdUnitOrgOwner())->whereIn('Id', $layananName)->orderBy('No')->get();

            $raw = '';
            foreach ($refStatusLayanan as $ref) {
                $raw .= "
                WHEN
                    [StatusLayanan] = " . $ref->Id . " THEN
                '" . $ref->Nama . " Ticket'
            ";
            }
        } else {
            $layananName = [1, 2, 3, 4, 5, 6];
            $raw = "
            WHEN
                [StatusLayanan] = 1 THEN
            'New Ticket'
            WHEN
                [StatusLayanan] = 2 THEN
            'Open Ticket'
            WHEN
                [StatusLayanan] = 3 THEN
            'In Progress Ticket'
            WHEN
                [StatusLayanan] = 4 THEN
            'Solved Ticket'
            WHEN
                [StatusLayanan] = 5 THEN
            'Closed Ticket'
            WHEN
                [StatusLayanan] = 6 THEN
            'Confirm'
        ";
        }
        $tickets = $this->layananService->getAllLayanan([], false)->whereBetween('CreatedAt', [$request->tglStart . ' 00:00:00', $request->tglEnd . ' 23:59:59'])->selectRaw("No,StatusLayanan,CASE
                " . $raw . "
                END Name,
                COUNT ( Layanan.Id ) Jumlah ")->whereIn('StatusLayanan', $layananName)
            ->leftJoin('RefStatusLayanan', 'RefStatusLayanan.Id', '=', 'Layanan.StatusLayanan')
            ->where('RefStatusLayanan.KdUnitOrgOwnerLayanan', kdUnitOrgOwner())
            ->groupBy('No', 'StatusLayanan')->orderBy('No')->orderBy('StatusLayanan')->get();
        $prioritasLayanan = $this->layananService->getAllLayanan([], false)
            ->where('Layanan.KdUnitOrgOwnerLayanan', kdUnitOrgOwner())->whereBetween('Layanan.CreatedAt', [$request->tglStart . ' 00:00:00', $request->tglEnd . ' 23:59:59'])->selectRaw("PrioritasLayanan, COUNT ( Id ) Jumlah ")->whereNotNull('PrioritasLayanan')->groupBy('PrioritasLayanan');
        $subBagLayanan = $this->layananService->getAllLayanan([], false)->where('Layanan.KdUnitOrgOwnerLayanan', kdUnitOrgOwner())->whereBetween('Layanan.CreatedAt', [$request->tglStart . ' 00:00:00', $request->tglEnd . ' 23:59:59'])->selectRaw("SpgUnitOrganisasi.KdUnitOrg,SpgUnitOrganisasi.NmUnitOrg, COUNT ( Layanan.Id ) Jumlah ")
            ->join('LayananGroupSolver', 'Layanan.Id', '=', 'LayananGroupSolver.LayananId')->join('SpgUnitOrganisasi', 'SpgUnitOrganisasi.KdUnitOrg', '=', 'LayananGroupSolver.MstGroupSolverId')->whereNull('LayananGroupSolver.DeletedAt')->groupBy('SpgUnitOrganisasi.KdUnitOrg', 'SpgUnitOrganisasi.NmUnitOrg')->orderBy('SpgUnitOrganisasi.KdUnitOrg');
        $pemenuhanSLA = $this->layananService->getAllLayanan([], false)->where('Layanan.KdUnitOrgOwnerLayanan', kdUnitOrgOwner())->whereBetween('Layanan.CreatedAt', [$request->tglStart . ' 00:00:00', $request->tglEnd . ' 23:59:59'])->selectRaw("       CASE     WHEN isnull(Limit,0) >= Datediff(hour, [tglticket], CASE WHEN [layanan].statuslayanan IN ( 5, 6, 7 ) THEN isnull([Layanan].TglSolved,[Layanan].[TglTicket]) ELSE Getdate() END) THEN 'Memenuhi SLA'
        WHEN isnull(Limit,0) < Datediff(hour, [tglticket], CASE WHEN [layanan].statuslayanan IN ( 5, 6, 7 ) THEN isnull([Layanan].TglSolved,[Layanan].[TglTicket]) ELSE Getdate() END) THEN 'Tidak Memenuhi SLA'
        END PemenuhanSLA,
        CASE
        WHEN isnull(Limit,0) >= Datediff(hour, [tglticket], CASE WHEN [layanan].statuslayanan IN ( 5, 6, 7 ) THEN isnull([Layanan].TglSolved,[Layanan].[TglTicket]) ELSE Getdate() END) THEN 'green'
        ELSE 'red'
        END Color,
        Count(layanan.id) Jumlah")
            ->join('servicecatalogdetail', 'Layanan.servicecatalogdetailid', '=', 'servicecatalogdetail.Id')->whereNotNull('NoTicket')->whereNotNull('ServiceCatalogDetailId')->groupByRaw("CASE  WHEN isnull(Limit,0) >= Datediff(hour, [tglticket], CASE WHEN [layanan].statuslayanan IN ( 5, 6, 7 ) THEN isnull([Layanan].TglSolved,[Layanan].[TglTicket]) ELSE Getdate() END) THEN 'Memenuhi SLA'
        WHEN isnull(Limit,0) < Datediff(hour, [tglticket], CASE WHEN [layanan].statuslayanan IN ( 5, 6, 7 ) THEN isnull([Layanan].TglSolved,[Layanan].[TglTicket]) ELSE Getdate() END) THEN 'Tidak Memenuhi SLA'
        END ,
        CASE
        WHEN isnull(Limit,0) >= Datediff(hour, [tglticket], CASE WHEN [layanan].statuslayanan IN ( 5, 6, 7 ) THEN isnull([Layanan].TglSolved,[Layanan].[TglTicket]) ELSE Getdate() END) THEN 'green'
        ELSE 'red'
        END  ");
        $mendekatiDeadline = $this->layananService->getAllLayanan([], false)->where('Layanan.KdUnitOrgOwnerLayanan', kdUnitOrgOwner())->whereBetween('Layanan.CreatedAt', [$request->tglStart . ' 00:00:00', $request->tglEnd . ' 23:59:59'])->selectRaw('count(Id) Jumlah')->whereIn('StatusLayanan', [2, 3])->whereIn('Id', function ($query) {
            $query->select('Id')
                ->from('vRptSLADeadline');
        });
        $solverLayanan = null;
        $Nip = auth()->user()->NIP;
        if (auth()->user()->can('dashboard.pie-solver.read')) {
            if (auth()->user()->hasRole('Pejabat Struktural')) {
                $solverLayanan = LayananSolver::selectRaw('LayananSolver.[Nip],SpgDataCurrent.NmPeg, count(LayananSolver.Id) Jumlah')->join('SpgDataCurrent', 'SpgDataCurrent.Nip', '=', 'LayananSolver.Nip')->join('Layanan', 'Layanan.Id', '=', 'LayananSolver.LayananId')->whereBetween('Layanan.CreatedAt', [$request->tglStart . ' 00:00:00', $request->tglEnd . ' 23:59:59'])->whereNull("Layanan.DeletedAt")->whereRaw("LayananSolver.[Nip] in (SELECT [Nip] FROM [Melati_V3].[dbo].[MstSolver] where [MstGroupSolverId] in (SELECT [MstGroupSolverId] FROM [Melati_V3].[dbo].[MstSolver] where Nip='{$Nip}'))")->groupByRaw("LayananSolver.Nip,SpgDataCurrent.NmPeg")->orderByRaw("count(LayananSolver.Id) DESC");
            } else {
                $solverLayanan = LayananSolver::selectRaw('LayananSolver.[Nip],SpgDataCurrent.NmPeg, count(LayananSolver.Id) Jumlah')->join('SpgDataCurrent', 'SpgDataCurrent.Nip', '=', 'LayananSolver.Nip')->join('Layanan', 'Layanan.Id', '=', 'LayananSolver.LayananId')->whereBetween('Layanan.CreatedAt', [$request->tglStart . ' 00:00:00', $request->tglEnd . ' 23:59:59'])->whereNull("Layanan.DeletedAt")->groupByRaw("LayananSolver.Nip,SpgDataCurrent.NmPeg")->orderByRaw("count(LayananSolver.Id) DESC");
            }
        }
        if ($request->statusLayanan && $request->statusLayanan != 'null') {
            $statusLayanan = explode(",", $request->statusLayanan);
            $prioritasLayanan->whereIn('StatusLayanan', $statusLayanan);
            $subBagLayanan->whereIn('StatusLayanan', $statusLayanan);
            $solverLayanan ? $solverLayanan->whereIn('StatusLayanan', $statusLayanan) : '';
        }
        if (!auth()->user()->can('dashboard.pie-group-solver.read')) {
            $groupSolver = Solver::where('Nip', auth()->user()->NIP)->pluck('MstGroupSolverId')->toArray();
            $subBagLayanan->whereIn('LayananGroupSolver.MstGroupSolverId ', $groupSolver);
        }
        $data = (object) [
            'tickets' => $tickets,
            'prioritasLayanan' => $prioritasLayanan->get(),
            'subBagLayanan' => $subBagLayanan->get(),
            'pemenuhanSLA' => $pemenuhanSLA->get(),
            'solverLayanan' => $solverLayanan ? $solverLayanan->where('Layanan.KdUnitOrgOwnerLayanan', kdUnitOrgOwner())->get() : '',
            'mendekatiDeadline' => $mendekatiDeadline->first()->Jumlah
        ];

        $response['data'] = $data;
        return response()->json($response);
    }
}
