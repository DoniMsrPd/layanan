<?php

namespace App\Http\Controllers\Layanan;

use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\Layanan\Layanan;
use App\Models\Layanan\LayananAset;
use App\Models\Layanan\LayananPersediaan;
use App\Models\Layanan\LayananTL;
use App\Models\Layanan\PersediaanDistribusi;
use App\Models\Layanan\VRptPersediaan;
use Modules\Layanan\Exports\BAPersediaanExport;
use App\Services\LayananService;
use Modules\Setting\Entities\Persediaan;
use Yajra\DataTables\Facades\DataTables;

class BaPersediaanController extends Controller
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
        logActivity('default', ' Manajemen Dokumen BA Persediaan')->log("View Manajemen Dokumen BA Persediaan");
        return view('layanan.ba-persediaan.index');
    }
    public function dataTables(Request $request)
    {
        $data = PersediaanDistribusi::whereNull('DeletedAt')->whereNotNull('NoBA')->filtered();
        return DataTables::of($data)->order(function ($query) {
            $query->orderBy('CreatedAt', 'DESC');
        })->addColumn('Ticket', function ($data) {
            $res =ToDmyHi($data->layanan->TglTicket) .'<br><a href="' . route('layanan.eskalasi', $data->LayananId) . '?kembali=ba-persediaan.index">' . optional($data->layanan)->NoTicket . '<br>' . strtoupper(optional($data->layanan)->NoTicketRandom) . '</a>';
            return $res;
        })->editColumn('TglBA', function ($data) {
            return ToDmy($data->TglBA);
        })->addColumn('action', function ($data) {

            $downloadPerbaikanBtn = '<a title="Download BA Persediaan" href="' . route('ba-persediaan.download', ['persediaanDistribusi' => $data->Id]) . '?" class="mb-2 mr-2 btn btn-warning btn-sm" target="_blank"><i
            class="os-icon os-icon-download"></i></a>';
            $perbaikanBtn = '';
            if (request()->user()->can('ba-persediaan.update'))
                $perbaikanBtn = '<a data-jenis="persediaan" data-id="' . $data->LayananTLId . '" class="mb-2 mr-2 btn btn-success btn-sm formBaPeminjaman text-white" data-title="Generate BA Persediaan" title="Edit BA Persediaan" title-pos="up"><i class="icon-feather-edit-2"></i></a>';
            return '<span class="btn-group" role="group">' . $perbaikanBtn . $downloadPerbaikanBtn . '</span>';
        })->rawColumns(['Ticket', 'action'])->make(true);
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
        $layananTl = LayananTL::find($request->id);
        $persediaanDistribusi = PersediaanDistribusi::where('LayananTLId', $request->id)->first();
        if (!$persediaanDistribusi) {
            $persediaanDistribusi = new PersediaanDistribusi;
            $noUrut = $layananService->noUrutBaPersediaan();
            $persediaanDistribusi->LayananId = $layananTl->LayananId;
            $persediaanDistribusi->LayananTLId = $layananTl->Id;
            $persediaanDistribusi->NoUrutBA = $noUrut;
            $persediaanDistribusi->NoBA = $noUrut . "/BA-PERSEDIAAN/X.5/" . date('m') . "/" . date('Y');
            $persediaanDistribusi->CreatedAt = Carbon::now();
            $persediaanDistribusi->CreatedBy = auth()->user()->NIP;
            $id = uuid();
            $persediaanDistribusi->Id = $id;
        } else {
            $persediaanDistribusi->UpdatedAt = Carbon::now();
            $persediaanDistribusi->UpdatedBy = auth()->user()->NIP;
        }
        $persediaanDistribusi->TglBA = $request->TglBA;

        if ($request->isPihakLuar == 'on') {
            $persediaanDistribusi->NipPihak1Luar = $request->NipPihak1Luar;
            $persediaanDistribusi->NmPihak1Luar = $request->NmPihak1Luar;
            $persediaanDistribusi->KdUnitOrgPihak1Luar = $request->KdUnitOrgPihak1Luar;
            $persediaanDistribusi->NipPihak1 = null;
            $persediaanDistribusi->NmPihak1 = null;
            $persediaanDistribusi->KdUnitOrgPihak1 = null;
        } else {
            $persediaanDistribusi->NipPihak1Luar = null;
            $persediaanDistribusi->NmPihak1Luar = null;
            $persediaanDistribusi->KdUnitOrgPihak1Luar = null;
            $persediaanDistribusi->NipPihak1 = $request->NipPihak1;
            $persediaanDistribusi->NmPihak1 = $request->NmPihak1;
            $persediaanDistribusi->KdUnitOrgPihak1 = $request->KdUnitOrgPihak1;
        }

        $persediaanDistribusi->NipPihak2 = $request->NipPihak2;
        $persediaanDistribusi->NmJabatanPihak2 = $request->NmJabatanPihak2;
        $persediaanDistribusi->NmUnitOrgPihak2 = $request->NmUnitOrgPihak2;
        $persediaanDistribusi->KdUnitOrgPihak2 = $request->KdUnitOrgPihak2;
        $persediaanDistribusi->NmJabatanPihak2 = $request->NmJabatanPihak2;
        $persediaanDistribusi->NipTtdPejabat = $request->NipTtdPejabat;
        $persediaanDistribusi->NmPegTtdPejabat = $request->NmPegTtdPejabat;
        $persediaanDistribusi->NmJabatanPejabat = $request->NmJabatanPejabat;
        $persediaanDistribusi->save();
        $response['message'] = 'Berhasil Generate';
        logActivity('default', ' Manajemen Dokumen BA Persediaan')->log("Generate BA Persediaan " . $persediaanDistribusi->Id ?? $id);
        return response()->json($response);
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function show($layananTLId, LayananService $layananService)
    {
        $persediaanDistribusi = PersediaanDistribusi::where('LayananTLId', $layananTLId)->first();
        if (!$persediaanDistribusi) {
            $layananTl = LayananTL::find($layananTLId);
            $layanan = Layanan::find($layananTl->LayananId);
            $persediaanDistribusi = (object) [];
            $persediaanDistribusi->persediaan = LayananPersediaan::where('LayananTLId', $layananTLId)->whereNull('DeletedAt')->get();
            $persediaanDistribusi->layanan = $layanan;
            $persediaanDistribusi->NoUrutBA = $layananService->noUrutBaPersediaan();
            $persediaanDistribusi->NoBA = $persediaanDistribusi->NoUrutBA . "/BA-PERSEDIAAN/X.5/" . date('m') . "/" . date('Y');
        } else {
            $persediaanDistribusi->persediaan = LayananPersediaan::where('LayananTLId', $layananTLId)->whereNull('DeletedAt')->get();
        }
        $response['success'] = true;
        $response['data'] = $persediaanDistribusi;
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
    public function download($persediaanDistribusi)
    {
        $persediaanDistribusi = PersediaanDistribusi::where('Id',$persediaanDistribusi)->orWhere('LayananTLId',$persediaanDistribusi)->first();
        logActivity('default', ' Manajemen Dokumen BA Persediaan')->log("Download BA Persediaan " . $persediaanDistribusi->Id);
        $pdf = PDF::loadview('layanan.ba-persediaan.ba-persediaan', ['data' => $persediaanDistribusi])->setPaper('a4', 'portrait');
        return $pdf->stream("BA Persediaan {$persediaanDistribusi->layanan->NoTicket}.pdf");
    }

    public function export(Request $request)
    {
        # code...
        $layananIds =  PersediaanDistribusi::whereNull('DeletedAt')->whereNotNull('NoBA')->filtered()->pluck('LayananId')->toArray();
        $data= VRptPersediaan::whereIn('LayananId',$layananIds)->get();
        return view('layanan.ba-persediaan.export', compact('data'));
        // return Excel::download(new BAPersediaanExport($data->get()), 'BA Persediaan '. Carbon::now() . '.xlsx');
    }
}
