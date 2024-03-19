<?php

namespace App\Http\Controllers\Layanan;

use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Excel;
use App\Models\Layanan\LayananAset;
use App\Models\Layanan\RefStatusLayanan;
use App\Services\LayananService;
use Yajra\DataTables\Facades\DataTables;

class BaPerbaikanController extends Controller
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
        $data = (object) [
            'statusLayanan' => RefStatusLayanan::get(),
            'solver' => DB::select("select distinct MstSolver.[Nip], NmPeg from [MstSolver] join SpgDataCurrent on SpgDataCurrent.Nip = MstSolver.Nip"),
        ];
        logActivity('default', ' Manajemen Dokumen BA Perbaikan')->log("View Manajemen Dokumen BA Perbaikan");
        return view('layanan.ba-perbaikan.index', compact('data'));
    }
    public function dataTables(Request $request)
    {
        $data = LayananAset::whereNull('LayananAset.DeletedAt')->whereNotNull('NoBA')->filtered();
        if ($request->sla) {
            $data->selectRaw("LayananAset.*");
            $data->leftJoin('Layanan', 'LayananAset.LayananId', '=', 'Layanan.Id');
            $data->leftJoin('ServiceCatalogDetail', 'Layanan.ServiceCatalogDetailId', '=', 'ServiceCatalogDetail.Id');
            $data->whereNotNull('NoTicket')->whereNotNull('ServiceCatalogDetailId');
            if (count($request->sla) == 1) {
                if ($request->sla[0] == 'melewati')
                    $data->whereRaw("isnull(Limit,0)  < Datediff(hour, [tglticket], CASE WHEN [layanan].statuslayanan IN ( 5, 6, 7 ) THEN isnull([Layanan].TglSolved,[Layanan].[TglTicket]) ELSE Getdate() END)");
                if ($request->sla[0] == 'tidak_melewati')
                    $data->whereRaw("isnull(Limit,0)  >= Datediff(hour, [tglticket], CASE WHEN [layanan].statuslayanan IN ( 5, 6, 7 ) THEN isnull([Layanan].TglSolved,[Layanan].[TglTicket]) ELSE Getdate() END)");
            }
        }
        return DataTables::of($data)->order(function ($query) {
            $query->orderBy('CreatedAt', 'DESC');
        })->addColumn('Ticket', function ($data) {
            $res = ToDmyHi($data->layanan->TglTicket) . '<br><a href="' . route('layanan.eskalasi', $data->LayananId) . '?kembali=ba-perbaikan.index">' . $data->layanan->NoTicket . '<br>' . strtoupper($data->layanan->NoTicketRandom) . '</a>';
            return $res;
        })->editColumn('TglBA', function ($data) {
            return ToDmy($data->TglBA);
        })->editColumn('TglKembali', function ($data) {
            return ToDmy($data->TglKembali);
        })->addColumn('StatusLayanan', function ($data) {
            return optional($data->layanan->status)->Nama;
        })->addColumn('Perbaikan', function ($data) {


            $downloadPerbaikanBtn = '<a title="Download BA Perbaikan" href="' . route('ba-perbaikan.download', ['layananAset' => $data->Id]) . '?jenis=terima" class="mb-2 mr-2 btn btn-warning btn-sm" target="_blank"><i
            class="os-icon os-icon-download"></i></a>';
            $perbaikanBtn = '';
            if (request()->user()->can('ba-perbaikan.update'))
                $perbaikanBtn = '<a data-jenis="terima" data-id="' . $data->Id . '" class="ml-2 mb-2 mr-2 btn btn-success btn-sm formBaAset text-white" data-title="Generate BA Perbaikan" title="Edit BA Perbaikan" title-pos="up"><i class="icon-feather-edit-2"></i></a>';
            $button = '<span class="btn-group" role="group">' . $perbaikanBtn . $downloadPerbaikanBtn . '</span>';
            return $data->NoBA.$button . '<br>' . ToDmy($data->TglBA) . '<br><span style="color:blue">' . $data->layanan->Nip . ' - ' . $data->layanan->NmPeg . '</span><br><span style="color:green">' . $data->NipPihak2 . ' - ' . optional($data->pihak2)->NmPeg.'</span>';
        })->addColumn('Pengembalian', function ($data) {
            $downloadPengembalianBtn = '';
            if ($data->NoBAPengembalian)
                $downloadPengembalianBtn = '<a title="Download BA Pengambilan" href="' . route('ba-perbaikan.download', ['layananAset' => $data->Id]) . '?jenis=ambil" class="mb-2 mr-2 btn btn-warning btn-sm" target="_blank"><i
                class="os-icon os-icon-download"></i></a>';
            $pengembalianBtn = '<a data-jenis="ambil" data-id="' . $data->Id . '" class="mb-2 mr-2 btn btn-primary btn-sm formBaAset text-white" data-title="Generate BA Pengambilan" data-pengembalian="1" title="Edit BA Pengembalian " title-pos="up"><i class="icon-feather-edit-2"></i></a>';
            $button = '<span class="btn-group" role="group">' . $pengembalianBtn . $downloadPengembalianBtn . '</span>';
            return $data->NoBAPengembalian.$button . '<br>' . ToDmy($data->TglKembali) . '<br><span style="color:blue">' . optional($data->pengembali)->Nip . ' - ' . optional($data->pengembali)->NmPeg . '</span><br><span style="color:green">' . optional($data->pihak2pengembalian)->Nip . ' - ' . optional($data->pihak2pengembalian)->NmPeg.'</span>';
        })->addColumn('action', function ($data) {

            $downloadPerbaikanBtn = '<a title="Download BA Perbaikan" href="' . route('ba-perbaikan.download', ['layananAset' => $data->Id]) . '?jenis=terima" class="mb-2 mr-2 btn btn-warning btn-sm" target="_blank"><i
            class="os-icon os-icon-download"></i></a>';
            $downloadPengembalianBtn = '';
            if ($data->NoBAPengembalian)
                $downloadPengembalianBtn = '<a title="Download BA Pengambilan" href="' . route('ba-perbaikan.download', ['layananAset' => $data->Id]) . '?jenis=ambil" class="mb-2 mr-2 btn btn-warning btn-sm" target="_blank"><i
                class="os-icon os-icon-download"></i></a>';
            $perbaikanBtn = '';
            $pengembalianBtn = '<a data-jenis="ambil" data-id="' . $data->Id . '" class="mb-2 mr-2 btn btn-primary btn-sm formBaAset text-white" data-title="Generate BA Pengambilan" data-pengembalian="1" title="Edit BA Pengembalian " title-pos="up"><i class="icon-feather-edit-2"></i></a>';
            if (request()->user()->can('ba-perbaikan.update'))
                $perbaikanBtn = '<a data-jenis="terima" data-id="' . $data->Id . '" class="mb-2 mr-2 btn btn-success btn-sm formBaAset text-white" data-title="Generate BA Perbaikan" title="Edit BA Perbaikan" title-pos="up"><i class="icon-feather-edit-2"></i></a>';
            return '<span class="btn-group" role="group">' . $perbaikanBtn . $downloadPerbaikanBtn . '</span><br>' . '<span class="btn-group" role="group">' . $pengembalianBtn . $downloadPengembalianBtn . '</span>';
        })->rawColumns(['Ticket', 'action','Perbaikan','Pengembalian'])->make(true);
    }
    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        return view('layanan.create');
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $request, LayananService $layananService)
    {
        //
        $layananAset = LayananAset::find($request->idAset);
        if ($request->jenisBA == 'terima') {
            if (!$layananAset->NoBA) {
                $layananAset->NoUrutBA = $layananService->noUrutBa();
                $layananAset->NoBA = $layananAset->NoUrutBA . "/BA-PERBAIKAN/X.5/" . date('m') . "/" . date('Y');
            }
            $layananAset->TglBA = $request->TglBA;
            $layananAset->Ruang = $request->Ruang;
            $layananAset->NipPihak2 = $request->NipPihak2;
            $layananAset->NmJabatanPihak2 = $request->NmJabatanPihak2;
            $layananAset->NmUnitOrgPihak2 = $request->NmUnitOrgPihak2;
            $layananAset->KdUnitOrgPihak2 = $request->KdUnitOrgPihak2;
            $layananAset->NmJabatanPihak2 = $request->NmJabatanPihak2;
        } else {
            if (!$layananAset->NoBAPengembalian) {
                $layananAset->NoUrutBAPengembalian = $layananService->noUrutBaPengembalian();
                $layananAset->NoBAPengembalian = $layananAset->NoUrutBAPengembalian . "/BA-PENGEMBALIAN/X.5/" . date('m') . "/" . date('Y');
            }
            $layananAset->TglKembali = $request->TglBA;
            $layananAset->NipPengembalianAsetPihak2 = $request->NipPihak2;
            $layananAset->NmJabatanPengembalianPihak2 = $request->NmJabatanPihak2;
            $layananAset->NmUnitOrgPengembalianPihak2 = $request->NmUnitOrgPihak2;
            $layananAset->KdUnitOrgPengembalianPihak2 = $request->KdUnitOrgPihak2;
            $layananAset->NmJabatanPengembalianPihak2 = $request->NmJabatanPihak2;
            $layananAset->NipPengembalianAset = $request->NipPengembalianAset;
            $layananAset->KdUnitOrgPengembalian = $request->KdUnitOrgPengembalian;
            $layananAset->KeteranganPengembalian = $request->KeteranganPengembalian;
        }
        $layananAset->NipTtdPejabat = $request->NipTtdPejabat;
        $layananAset->NmPegTtdPejabat = $request->NmPegTtdPejabat;
        $layananAset->NmJabatanPejabat = $request->NmJabatanPejabat;
        if ($request->fisikPengembalian) {

            $layananAset->Fisik = $request->fisikPengembalian;
            $layananAset->Kelengkapan = $request->kelengkapanPengembalian;
            $layananAset->Data = $request->dataPengembalian;
            $layananAset->NoBox = $request->noBoxPengembalian;
            $layananAset->Keterangan = $request->keteranganAsetLain;
        }
        $layananAset->UpdatedAt = Carbon::now();
        $layananAset->UpdatedBy = auth()->user()->NIP;
        $layananAset->save();
        $response['message'] = 'Berhasil Generate';
        logActivity('default', ' Manajemen Dokumen BA Perbaikan')->log("Generate BA Perbaikan " . $layananAset->Id);
        return response()->json($response);
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function show(LayananAset $layananAset, LayananService $layananService)
    {
        if (!$layananAset->NoBA) {
            $layananAset->NoUrutBABaru = $layananService->noUrutBa();
            $layananAset->NoBABaru = $layananAset->NoUrutBABaru . "/BA-PERBAIKAN/X.5/" . date('m') . "/" . date('Y');
        }
        if (!$layananAset->NoBAPengembalian) {
            $layananAset->NoUrutBAPengembalianBaru = $layananService->noUrutBaPengembalian();
            $layananAset->NoBAPengembalianBaru = $layananAset->NoUrutBAPengembalianBaru . "/BA-PENGEMBALIAN/X.5/" . date('m') . "/" . date('Y');
        }
        $layananAset->KeteranganDefault =  DB::select("SELECT TOP 1 * FROM RefKeteranganUjiFungsi")[0]->Keterangan;
        $response['success'] = true;
        $response['data'] = $layananAset;
        return response()->json($response);
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        return view('layanan.edit');
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Renderable
     */

    public function destroy(LayananAset $ba_perbaikan)
    {
        $this->authorize('ba-perbaikan.delete');
        $response = self::$response;
        $delete = false;

        try {
            $ba_perbaikan->DeletedAt = Carbon::now()->format('Y-m-d H:i:s');
            $ba_perbaikan->DeletedBy = auth()->user()->NIP;
            $delete = $ba_perbaikan->save();
            $response['success'] = $delete;
            $response['message'] = $delete ? 'Berhasil Hapus Data' : 'Gagal Hapus Data';
            logActivity('default', ' Manajemen Dokumen BA Perbaikan')->log("Delete BA Perbaikan " . $ba_perbaikan->Id);

            return response()->json($response);
        } catch (\Exception $e) {
            $response['success'] = false;
            $response['message'] = $e->getMessage();
            return response()->json($response);
        }
    }
    public function download(LayananAset $layananAset)
    {
        logActivity('default', ' Manajemen Dokumen BA Perbaikan')->log("Download BA Perbaikan " . $layananAset->Id);
        $pdf = PDF::loadview('layanan.ba-perbaikan.ba-aset', ['data' => $layananAset])->setPaper('a4', 'portrait');
        return $pdf->stream("BA Aset {$layananAset->layanan->NoTicket}.pdf");
    }
    public function export(Request $request)
    {
        # code...
        $data = LayananAset::whereNull('LayananAset.DeletedAt')->whereNotNull('NoBA')->filtered();
        if ($request->sla) {
            $data->selectRaw("LayananAset.*,NormaWaktu,Limit,Datediff(hour, [tglticket], CASE WHEN [layanan].statuslayanan IN ( 5, 6, 7 ) THEN isnull([Layanan].TglSolved,[Layanan].[TglTicket]) ELSE Getdate() END) LamaJamLayanan");
            $data->leftJoin('Layanan', 'LayananAset.LayananId', '=', 'Layanan.Id');
            $data->leftJoin('ServiceCatalogDetail', 'Layanan.ServiceCatalogDetailId', '=', 'ServiceCatalogDetail.Id');
            $data->whereNotNull('NoTicket')->whereNotNull('ServiceCatalogDetailId');
            if (count($request->sla) == 1) {
                if ($request->sla[0] == 'melewati')
                    $data->whereRaw("isnull(Limit,0)  < Datediff(hour, [tglticket], CASE WHEN [layanan].statuslayanan IN ( 5, 6, 7 ) THEN isnull([Layanan].TglSolved,[Layanan].[TglTicket]) ELSE Getdate() END)");
                if ($request->sla[0] == 'tidak_melewati')
                    $data->whereRaw("isnull(Limit,0)  >= Datediff(hour, [tglticket], CASE WHEN [layanan].statuslayanan IN ( 5, 6, 7 ) THEN isnull([Layanan].TglSolved,[Layanan].[TglTicket]) ELSE Getdate() END)");
            }
        }
        $data = $data->get();
        return view('layanan.ba-perbaikan.export', compact('data'));
    }
}
