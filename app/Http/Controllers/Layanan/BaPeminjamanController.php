<?php

namespace App\Http\Controllers\Layanan;

use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use App\Models\Layanan\LayananAset;
use App\Models\Layanan\Peminjaman;
use App\Models\Layanan\PeminjamanDetail;
use App\Models\Layanan\Pengembalian;
use App\Services\LayananService;
use Yajra\DataTables\Facades\DataTables;

class BaPeminjamanController extends Controller
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
        logActivity('default', ' Manajemen Dokumen BA Peminjaman')->log("View Manajemen Dokumen BA Peminjaman");
        return view('layanan.ba-peminjaman.index');
    }
    public function dataTables(Request $request)
    {
        $data = Peminjaman::with('layanan')->whereNull('DeletedAt')->whereNotNull('NoBA')->filtered();
        return DataTables::of($data)->order(function ($query) {
            $query->orderBy('CreatedAt', 'DESC');
        })->addColumn('Ticket', function ($data) {
            $res = ToDmyHi($data->layanan->TglTicket).'<br><a href="' .route('layanan.eskalasi', $data->LayananId) . '?kembali=ba-peminjaman.index">' . $data->layanan->NoTicket . '<br>' . strtoupper($data->layanan->NoTicketRandom) . '</a><br><br>';
            $res .= $data->RefStatusPeminjamanId<>3 ?  '<a class="mb-2 mr-2 btn btn-primary btn-sm text-white"  href="' . route('ba-peminjaman.lihat', ['peminjaman' => $data->Id]) . '" title="Pengembalian"><i class="icon-feather-corner-down-left"></i></a>':'';
            return $res;
        })->addColumn('Status', function ($data) {
            return $data->status->Nama;
        })->addColumn('Peminjaman', function ($data) {


            $downloadPerbaikanBtn = '<a title="Download BA Peminjaman" href="' . route('ba-peminjaman.download', ['peminjaman' => $data->Id]) . '?jenis=pinjam" class="mb-2 mr-2 btn btn-warning btn-sm" target="_blank"><i
            class="os-icon os-icon-download"></i></a>';
            $perbaikanBtn = '';
            if (request()->user()->can('ba-peminjaman.update'))
                $perbaikanBtn = '<a data-jenis="pinjam" data-id="' . $data->Id . '" class="ml-2 mb-2 mr-2 btn btn-success btn-sm formBaPeminjaman text-white" data-title="Generate BA Peminjaman" title="Edit BA Peminjaman" title-pos="up"><i class="icon-feather-edit-2"></i></a>';
            $button = '<span class="btn-group" role="group">' . $perbaikanBtn . $downloadPerbaikanBtn . '</span>';
            $NmPihak1 = $data->NmPihak1 ?? $data->NmPihak1Luar;
            $NipPihak1 = $data->NipPihak1 ?? $data->NipPihak1Luar;
            return $data->NoBA.$button . '<br>' . ToDmy($data->TglBA) . '<br><span style="color:blue">' . $NipPihak1 . ' - ' . $NmPihak1 . '</span><br><span style="color:green">' . $data->NipPihak2 . ' - ' . $data->pihak2->NmPeg.'</span>';
        })->addColumn('Pengembalian', function ($data) {
            $result = '';
            foreach ($data->pengembalian as $item) {
                $downloadPengembalianBtn = '<a title="Download BA Pengembalian" href="' . route('ba-peminjaman.download', ['peminjaman' => $item->Id]) . '?jenis=kembali" class="mb-2 mr-2 btn btn-warning btn-sm" target="_blank"><i
                class="os-icon os-icon-download"></i></a>';
                $pengembalianBtn = '';
                if (request()->user()->can('ba-peminjaman.update'))
                    $pengembalianBtn = '<a data-jenis="kembali" data-id="' . $item->Id . '" class="mb-2 ml-2 btn btn-success btn-sm formBaPeminjaman text-white" data-title="Generate BA Pengembalian" title="Edit BA Pengembalian" title-pos="up"><i class="icon-feather-edit-2"></i></a>';
                $button =  '<span class="btn-group" role="group">' . $pengembalianBtn . $downloadPengembalianBtn . '</span>';
                $NmPihak1 = $item->NmPihak1 ?? $item->NmPihak1Luar;
                $NipPihak1 = $item->NipPihak1 ?? $item->NipPihak1Luar;
                $result .= $item->NoBA . $button . '<br>' . ToDmy($item->TglBA) . '<br><span style="color:blue">' . $NipPihak1 . ' - ' . $NmPihak1 . '</span><br><span style="color:green">' . $item->NipPihak2 . ' - ' . $item->pihak2->NmPeg . '<br></span><hr>';
            }
            return $result;
        })->editColumn('TglBA', function ($data) {
            return ToDmy($data->TglBA);
        })->editColumn('TglKembali', function ($data) {
            return ToDmy($data->TglKembali);
        })->addColumn('action', function ($data) {
        })->rawColumns(['Ticket','Status', 'NoTicket','Peminjaman', 'Pengembalian', 'action'])->make(true);
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
        $peminjaman = Peminjaman::find($request->id);
        if (!$peminjaman->NoBA) {
            $peminjaman->NoUrutBA = $layananService->noUrutBaPeminjaman();
            $peminjaman->NoBA = $peminjaman->NoUrutBA . "/BA-PEMINJAMAN/X.5/" . date('m') . "/" . date('Y');
        }
        $peminjaman->TglBA = $request->TglBA;
        $peminjaman->Ruang = $request->Ruang;
        $peminjaman->NipPihak2 = $request->NipPihak2;
        $peminjaman->NmJabatanPihak2 = $request->NmJabatanPihak2;
        $peminjaman->NmUnitOrgPihak2 = $request->NmUnitOrgPihak2;
        $peminjaman->KdUnitOrgPihak2 = $request->KdUnitOrgPihak2;
        $peminjaman->NmJabatanPihak2 = $request->NmJabatanPihak2;
        $peminjaman->NipTtdPejabat = $request->NipTtdPejabat;
        $peminjaman->NmPegTtdPejabat = $request->NmPegTtdPejabat;
        $peminjaman->NmJabatanPejabat = $request->NmJabatanPejabat;
        if (!$peminjaman->RefStatusPeminjamanId)
            $peminjaman->RefStatusPeminjamanId = 1;
        if ($request->isPihakLuar == 'on') {
            $peminjaman->NipPihak1 = null;
            $peminjaman->NmPihak1 = null;
            $peminjaman->KdUnitOrgPihak1 = null;
            $peminjaman->NipPihak1Luar = $request->NipPihak1Luar;
            $peminjaman->NmPihak1Luar = $request->NmPihak1Luar;
            $peminjaman->KdUnitOrgPihak1Luar = $request->KdUnitOrgPihak1Luar;
        } else {

            $peminjaman->NipPihak1 = $request->NipPihak1;
            $peminjaman->NmPihak1 = $request->NmPihak1;
            $peminjaman->KdUnitOrgPihak1 = $request->KdUnitOrgPihak1;
            $peminjaman->NipPihak1Luar = null;
            $peminjaman->NmPihak1Luar = null;
            $peminjaman->KdUnitOrgPihak1Luar = null;
        }
        $peminjaman->UpdatedAt = Carbon::now();
        $peminjaman->UpdatedBy = auth()->user()->NIP;
        $peminjaman->save();
        $response['message'] = 'Berhasil Generate';
        logActivity('default', ' Manajemen Dokumen BA Peminjaman')->log("Generate BA Peminjaman " . $peminjaman->Id);
        return response()->json($response);
    }
    /**
     * Show the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function show(Peminjaman $baPeminjaman, LayananService $layananService)
    {
        if (!$baPeminjaman->NoBA) {
            $baPeminjaman->NoUrutBABaru = $layananService->noUrutBaPeminjaman();
            $baPeminjaman->NoBABaru = $baPeminjaman->NoUrutBABaru . "/BA-PEMINJAMAN/X.5/" . date('m') . "/" . date('Y');
        }
        $response['success'] = true;
        $response['data'] = $baPeminjaman;
        return response()->json($response);
    }
    public function showPengembalian(Pengembalian $pengembalian, LayananService $layananService)
    {
        $response['success'] = true;
        $response['data'] = $pengembalian;
        return response()->json($response);
    }
    public function getDetailPengembalian(Request $request, LayananService $layananService)
    {
        $pengembalianDetail = PeminjamanDetail::whereIn('Id', $request->peminjamanDetailId)->get();
        $data = [
            'noBa' => $layananService->noUrutBaPengembalian2() . "/BA-PENGEMBALIAN/X.5/" . date('m') . "/" . date('Y'),
            'peminjaman' => Peminjaman::find($request->peminjamanId),
            'pengembalianDetail' => $pengembalianDetail
        ];
        $response['success'] = true;
        $response['data'] = $data;
        return response()->json($response);
    }
    public function inputPengembalian($request)
    {
        $inputPengembalian['TglBA'] = $request->TglBA;
        $inputPengembalian['Ruang'] = $request->Ruang;
        $inputPengembalian['NipPihak2'] = $request->NipPihak2;
        $inputPengembalian['NmJabatanPihak2'] = $request->NmJabatanPihak2;
        $inputPengembalian['NmUnitOrgPihak2'] = $request->NmUnitOrgPihak2;
        $inputPengembalian['KdUnitOrgPihak2'] = $request->KdUnitOrgPihak2;
        $inputPengembalian['NmJabatanPihak2'] = $request->NmJabatanPihak2;
        $inputPengembalian['NipTtdPejabat'] = $request->NipTtdPejabat;
        $inputPengembalian['NmPegTtdPejabat'] = $request->NmPegTtdPejabat;
        $inputPengembalian['NmJabatanPejabat'] = $request->NmJabatanPejabat;
        if ($request->isPihakLuar == 'on') {
            $inputPengembalian['NipPihak1'] = null;
            $inputPengembalian['NmPihak1'] = null;
            $inputPengembalian['KdUnitOrgPihak1'] = null;
            $inputPengembalian['NipPihak1Luar'] = $request->NipPihak1Luar;
            $inputPengembalian['NmPihak1Luar'] = $request->NmPihak1Luar;
            $inputPengembalian['KdUnitOrgPihak1Luar'] = $request->KdUnitOrgPihak1Luar;
        } else {

            $inputPengembalian['NipPihak1'] = $request->NipPihak1;
            $inputPengembalian['NmPihak1'] = $request->NmPihak1;
            $inputPengembalian['KdUnitOrgPihak1'] = $request->KdUnitOrgPihak1;
            $inputPengembalian['NipPihak1Luar'] = null;
            $inputPengembalian['NmPihak1Luar'] = null;
            $inputPengembalian['KdUnitOrgPihak1Luar'] = null;
        }
        $inputPengembalian['CreatedAt'] = Carbon::now();
        $inputPengembalian['CreatedBy'] = auth()->user()->NIP;
        return $inputPengembalian;
    }
    public function storePengembalian(Request $request, LayananService $layananService)
    {

        DB::beginTransaction();
        try {
            $peminjaman = Peminjaman::find($request->peminjamanId);
            $inputPengembalian = $this->inputPengembalian($request);
            $inputPengembalian['LayananId'] = $peminjaman->layananTl->LayananId;
            $inputPengembalian['LayananTLId'] = $peminjaman->layananTl->Id;
            $inputPengembalian['NoUrutBA'] = $layananService->noUrutBaPengembalian2();
            $inputPengembalian['NoBA'] = $inputPengembalian['NoUrutBA'] . "/BA-PENGEMBALIAN/X.5/" . date('m') . "/" . date('Y');
            $inputPengembalian['Id'] = uuid();
            $inputPengembalian['CreatedAt'] = Carbon::now();
            $inputPengembalian['CreatedBy'] = auth()->user()->NIP;
            $inputPengembalian['PeminjamanId'] = $request->peminjamanId;
            Pengembalian::create($inputPengembalian);
            $asetDikembalikan = 0;
            $asetBelumDikembalikan = $peminjaman->peminjamanDetailBelumDikembalikan->count();
            if ($request->peminjamanDetailId) {
                for ($i = 0; $i < count($request->peminjamanDetailId); $i++) {
                    if ($request->peminjamanDetailId[$i]) {
                        $peminjamanDetail = PeminjamanDetail::find($request->peminjamanDetailId[$i]);
                        $peminjamanDetail->PengembalianId = $inputPengembalian['Id'];
                        $peminjamanDetail->KeteranganPengembalian = $request->keteranganPengembalian[$i];
                        $peminjamanDetail->save();
                        $asetDikembalikan += 1;
                    }
                }
            }
            if ($asetBelumDikembalikan == $asetDikembalikan) {
                $peminjaman->RefStatusPeminjamanId = 3;
            } else {
                $peminjaman->RefStatusPeminjamanId = 2;
            }
            $peminjaman->save();
            $response['message'] = 'Berhasil Generate';
            logActivity('default', ' Manajemen Dokumen BA Peminjaman')->log("Generate BA Pengembalian " . $inputPengembalian['Id']);
            DB::commit();
        } catch (\Throwable $e) {
            DB::rollback();
            $response['message'] = 'Gagal Generate';
            \Log::error($e->getTraceAsString());
        }
        return response()->json($response);
    }
    public function updatePengembalian(Request $request)
    {

        DB::beginTransaction();
        try {
            $inputPengembalian = $this->inputPengembalian($request);
            $inputPengembalian['UpdatedAt'] = Carbon::now();
            $inputPengembalian['UpdatedBy'] = auth()->user()->NIP;
            Pengembalian::where('Id', $request->id)->update($inputPengembalian);
            $response['message'] = 'Berhasil Generate';
            logActivity('default', ' Manajemen Dokumen BA Peminjaman')->log("Generate BA Pengembalian " . $request->id);
            DB::commit();
        } catch (\Throwable $e) {
            DB::rollback();
            $response['message'] = $e->getTraceAsString();
            \Log::error($e->getTraceAsString());
        }
        return response()->json($response);
    }
    public function lihat(Peminjaman $peminjaman)
    {
        $data = (object)[
            'peminjaman' => $peminjaman
        ];
        return view('layanan.ba-peminjaman.lihat', compact('data'));
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
        $this->authorize('ba-peminjaman.delete');
        $response = self::$response;
        $delete = false;

        try {
            $ba_perbaikan->DeletedAt = Carbon::now()->format('Y-m-d H:i:s');
            $ba_perbaikan->DeletedBy = auth()->user()->NIP;
            $delete = $ba_perbaikan->save();
            $response['success'] = $delete;
            $response['message'] = $delete ? 'Berhasil Hapus Data' : 'Gagal Hapus Data';
            logActivity('default', ' Manajemen Dokumen BA Peminjaman')->log("Delete BA Peminjaman " . $ba_perbaikan->Id);

            return response()->json($response);
        } catch (\Exception $e) {
            $response['success'] = false;
            $response['message'] = $e->getMessage();
            return response()->json($response);
        }
    }
    public function download($peminjaman)
    {
        $data = Peminjaman::find($peminjaman);
        $msg = 'Peminjaman';
        if(request()->jenis=='kembali'){
            $data = Pengembalian::find($peminjaman);
            $msg = 'Pengembalian';
        }
        logActivity('default', ' Manajemen Dokumen BA Peminjaman')->log("Download BA Pengembalian " . $data->Id);
        $pdf = PDF::loadview('layanan.ba-peminjaman.ba-peminjaman', ['data' => $data])->setPaper('a4', 'portrait');
        return $pdf->stream("BA Peminjaman {$data->layanan->NoTicket}.pdf");
    }
    public function export(Request $request)
    {
        # code...
        $data = Peminjaman::with('layanan')->whereNull('DeletedAt')->whereNotNull('NoBA')->filtered();
        $data = $data->get();
        return view('layanan.ba-peminjaman.export', compact('data'));
    }
}
