<?php

namespace App\Http\Controllers\Layanan;

use App\Http\Controllers\Controller;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Layanan\Layanan;
use App\Models\Layanan\LayananAset;
use App\Models\Layanan\LayananGroupSolver;
use App\Models\Layanan\LayananLog;
use App\Models\Layanan\LayananNotifikasi;
use App\Models\Layanan\LayananPersediaan;
use App\Models\Layanan\LayananSolver;
use App\Models\Layanan\LayananTL;
use App\Models\Layanan\Peminjaman;
use App\Models\Layanan\PeminjamanDetail;
use App\Models\Layanan\RefStatusLayanan;
use App\Services\LayananService;
use App\Models\System\Pegawai;

class TindakLanjutController extends Controller
{

    private static $response = [
        'success' => false,
        'data'    => null,
        'message' => null
    ];
    public function index(Request $request)
    {
        $data = (object) [
            'tl' => LayananTL::where('LayananId', $request->LayananId)->filtered()->whereNull('DeletedAt')->get(),
            'kdUnit' => Layanan::select(['KdUnitOrgOwnerLayanan'])->where('Id', request()->LayananId)->first()->KdUnitOrgOwnerLayanan,
        ];
        $view = view('layanan.layanan._list-tl', compact('data'))->render();
        return response()->json($view, 200);
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */

     public function insertOrUpdatePeminjamanDetail($request,$LayananTLId)
     {

        $cekPeminjaman = Peminjaman::where('LayananTLId',$LayananTLId)->first();
        if(!$cekPeminjaman){
            $inputPeminjaman['Id'] = uuid();
            $inputPeminjaman['LayananId'] = $request->LayananId;
            $inputPeminjaman['LayananTLId'] = $LayananTLId;
            $inputPeminjaman['CreatedAt'] = Carbon::now();
            $inputPeminjaman['CreatedBy'] = auth()->user()->NIP;
            Peminjaman::create($inputPeminjaman);
        }
        for ($i = 0; $i < count($request->asetLayananId); $i++) {
            PeminjamanDetail::updateOrCreate(
                [
                    'Id' => $request->peminjamanDetailId[$i]
                ],
                [
                    'PeminjamanId' => $inputPeminjaman['Id'] ?? $cekPeminjaman->Id,
                    'KeteranganPeminjaman' => $request->keteranganPeminjaman[$i],
                    'AsetLayananId' => $request->asetLayananId[$i],
                ]
            );
        }
     }
    public function store(Request $request, LayananService $layananService)
    {
        DB::beginTransaction();
        try {
            $layanan = Layanan::findOrFail($request->LayananId);
            $inputTL['Id'] = uuid();
            $inputTL['LayananId'] = $request->LayananId;
            $inputTL['Keterangan'] = $request->Keterangan;
            $inputTL['StatusLayanan'] = $request->StatusLayanan;
            $inputTL['Nip'] = auth()->user()->NIP;
            $inputTL['CreatedAt'] = Carbon::now();
            $inputTL['CreatedBy'] = auth()->user()->NIP;
            LayananTL::create($inputTL);
            $layanan->UpdatedAt = Carbon::now();
            $layanan->UpdatedBy = auth()->user()->NIP;
            $layanan->save();
            //IsPersediaang
            if ($request->persediaanId) {
                for ($i = 0; $i < count($request->persediaanId); $i++) {
                    LayananPersediaan::updateOrCreate(
                        [
                            'Id' => $request->persediaanId[$i]
                        ],
                        [
                            'MstPersediaanId' => $request->mstPersediaanId[$i],
                            'Qty' => $request->qtyPersediaan[$i],
                            'Keterangan' => $request->keteranganPersediaan[$i],
                            'LayananTLId' => $inputTL['Id'],
                            'LayananId' => $request->LayananId,
                        ]
                    );
                }
                $this->inputLayananLog($request, $inputTL['Id'], $layanan, null, 'Tambah Persediaan Layanan',$layanan->StatusLayanan);
            }
            // IsPerbaikan
            if ($request->layananAsetId) {
                for ($i = 0; $i < count($request->layananAsetId); $i++) {
                    $data =
                        [
                            'AsetLayananId' => $request->asetId[$i],
                            'Keterangan' => $request->keteranganAset[$i],
                            'Fisik' => $request->fisik[$i],
                            'Kelengkapan' => $request->kelengkapan[$i],
                            'Data' => $request->data[$i],
                            'NoBox' => $request->noBox[$i],
                            'LayananTLId' => $inputTL['Id'],
                            'LayananId' => $request->LayananId,
                        ];
                    if ($request->isAsetSMA[$i])
                        $data =
                            [
                                'AsetSMAId' => $request->asetId[$i],
                                'Keterangan' => $request->keteranganAset[$i],
                                'Fisik' => $request->fisik[$i],
                                'Kelengkapan' => $request->kelengkapan[$i],
                                'Data' => $request->data[$i],
                                'NoBox' => $request->noBox[$i],
                                'LayananTLId' => $inputTL['Id'],
                                'LayananId' => $request->LayananId,
                            ];
                    LayananAset::updateOrCreate(
                        [
                            'Id' => $request->layananAsetId[$i]
                        ],
                        $data
                    );
                }
                $this->inputLayananLog($request, $inputTL['Id'], $layanan, null, 'Tambah Aset Layanan',$layanan->StatusLayanan);
            }
            // IsPeminjaman
            if ($request->asetLayananId) {
                $this->insertOrUpdatePeminjamanDetail($request,$inputTL['Id']);
            }
            inputFiles($request, $inputTL['Id'], 'FileAttachment', null, 'LayananTL');
            if ($request->Keterangan) {
                $this->inputLayananLog($request, $inputTL['Id'], $layanan, null, 'Create TL',$layanan->StatusLayanan);
            }
            if ($request->StatusLayanan && $layanan->StatusLayanan != $request->StatusLayanan) {
                $this->inputLayananLog($request, $inputTL['Id'], $layanan, null, 'Update Status',$layanan->StatusLayanan);
                $layanan->StatusLayanan = $request->StatusLayanan;
            }
            logActivity('default', 'Layanan')->log("Add Tindaklanjut Layanan {$request->LayananId}");
            if ($request->StatusLayanan == 6) {
                $statusLayanan = RefStatusLayanan::filterOrg($request->StatusLayanan)->first();
                $subject = "Layanan {$layanan->NoTicket} {$layanan->NoTicketRandom} :: {$statusLayanan->Nama}";
                $data = [
                    'layanan' => $layanan,
                    'url' => route('layanan.eskalasi', $layanan->Id),
                    'message' => 'Silahkan klik tombol dibawah ini untuk melihat detil layanan dan memantau proses penyelesaian layanan'
                ];
                if ($layanan->KdUnitOrgOwnerLayanan != '100205000000') {
                    $mst = LayananGroupSolver::where('LayananId', $layanan->Id)->pluck('MstGroupSolverId')->toArray();
                    $ketuaGroup = Pegawai::whereIn('KdUnitOrg',$mst)->where('StsPensiun',0)->where('JnsJabatanCur',1)->pluck('NIP')->toArray();
                    $ketua = User::whereIn('NIP', $ketuaGroup)->get();
                    foreach ($ketua as $to) {
                        $data['user'] = $to;
                        $view = view('layanan.notifications.wa', compact('data'))->render();
                        $layananService->sendMail($subject, 'layanan.notifications.mail', $data, $view, 'Melati ', $to);
                    }

                } else {
                    $to = User::where('NIP', $layanan->Nip)->first();
                    $data['user'] = $to;
                    $view = view('layanan.notifications.wa', compact('data'))->render();
                    $layananService->sendMail($subject, 'layanan.notifications.mail', $data, $view, 'Melati ', $to);
                }
                $input['CreatedAt'] = Carbon::now();
                $input['Nip'] = $layanan->Nip;
                $input['Jenis'] = 'Confirm';
                $input['LayananId'] = $layanan->Id;
                LayananNotifikasi::create($input);
            } elseif ($request->StatusLayanan == 7) {
                $statusLayanan = RefStatusLayanan::filterOrg($request->StatusLayanan)->first();
                $subject = "Layanan {$layanan->NoTicket} {$layanan->NoTicketRandom} :: {$statusLayanan->Nama}";
                $data = [
                    'layanan' => $layanan,
                    'url' => route('layanan.eskalasi', $layanan->Id),
                    'message' => 'Silahkan klik tombol dibawah ini untuk melihat detil layanan dan memantau proses penyelesaian layanan'
                ];
                if ($layanan->KdUnitOrgOwnerLayanan != '100205000000') {
                    $solverLayanan = LayananSolver::where('LayananId',$layanan->Id)->pluck('Nip')->toArray();
                    $solver = User::whereIn('Nip',$solverLayanan)->get();
                    foreach ($solver as $item) {
                        $data['user'] = $item;
                        $view = view('layanan.notifications.wa', compact('data'))->render();
                        $layananService->sendMail($subject, 'layanan.notifications.mail', $data, $view, 'Melati ', $item);
                    }
                }
                $input['CreatedAt'] = Carbon::now();
                $input['Nip'] = $layanan->Nip;
                $input['Jenis'] = 'Confirm';
                $input['LayananId'] = $layanan->Id;
                LayananNotifikasi::create($input);
            } elseif (in_array($request->StatusLayanan, [4, 5])) {
                $cek = LayananNotifikasi::whereIn('Jenis', ['Solved', 'Closed'])->where('LayananId', $layanan->Id)->first();
                if($request->StatusLayanan==4){
                    $layanan->TglSolved = Carbon::now();
                } else{
                    $layanan->TglClosed = Carbon::now();
                }
                // if ($cek) {
                    $status = $request->StatusLayanan == 4 ? 'Solved' : 'Closed';
                    $subject = "Layanan {$layanan->NoTicket} {$layanan->NoTicketRandom} :: $status";
                    $to = User::where('NIP', $layanan->Nip)->first();
                    $data = [
                        'layanan' => $layanan,
                        'url' => route('layanan.eskalasi', $layanan->Id),
                        'message' => 'Silahkan klik tombol dibawah ini untuk melihat detil layanan dan memantau proses penyelesaian layanan'
                    ];
                    if ($layanan->KdUnitOrgOwnerLayanan != '100205000000') {
                        $solverLayanan = LayananSolver::where('LayananId',$layanan->Id)->pluck('Nip')->toArray();
                        $solver = User::whereIn('Nip',$solverLayanan)->get();
                        foreach ($solver as $item) {
                            $data['user'] = $item;
                            $view = view('layanan.notifications.wa', compact('data'))->render();
                            $layananService->sendMail($subject, 'layanan.notifications.mail', $data, $view, 'Melati ', $item);
                        }
                        if ($request->StatusLayanan == 4) {
                            $operator = User::filterByRole($layanan->KdUnitOrgOwnerLayanan, ['Operator'])->first();
                            $data['user'] = $operator;
                            $view = view('layanan.notifications.wa', compact('data'))->render();
                            $layananService->sendMail($subject, 'layanan.notifications.mail', $data, $view, 'Melati ', $operator);
                        } else {
                            $mst = LayananGroupSolver::where('LayananId', $layanan->Id)->pluck('MstGroupSolverId')->toArray();
                            $ketuaGroup = Pegawai::whereIn('KdUnitOrg',$mst)->where('StsPensiun',0)->where('JnsJabatanCur',1)->pluck('Nip')->toArray();
                            $ketuaGroup = User::whereIn('NIP', $ketuaGroup)->get();
                            foreach ($ketuaGroup as $value) {
                                $data['user'] = $value;
                                $view = view('layanan.notifications.wa', compact('data'))->render();
                                $layananService->sendMail($subject, 'layanan.notifications.mail', $data, $view, 'Melati ', $value);
                            }
                        }
                    }
                    $data['user'] = $to;
                    $view = view('layanan.notifications.wa', compact('data'))->render();
                    $layananService->sendMail($subject, 'layanan.notifications.mail', $data, $view, 'Melati ', $to);
                    $input['CreatedAt'] = Carbon::now();
                    $input['Nip'] = $layanan->Nip;
                    $input['Jenis'] = $status;
                    $input['LayananId'] = $layanan->Id;
                    LayananNotifikasi::create($input);
                // }
            } elseif($request->StatusLayanan == 3) {
                $layanan->save();
                $layanan->load('status');
                $subject = "Layanan {$layanan->NoTicket} {$layanan->NoTicketRandom} :: {$layanan->status->Nama}";
                $to = User::where('NIP', $layanan->Nip)->first();
                $data = [
                    'user' => $to,
                    'layanan' => $layanan,
                    'url' => route('layanan.eskalasi', $layanan->Id),
                    'message' => 'Silahkan klik tombol dibawah ini untuk melihat detil layanan dan memantau proses penyelesaian layanan'
                ];
                $view = view('layanan.notifications.wa', compact('data'))->render();
                if ($layanan->KdUnitOrgOwnerLayanan != '100205000000') {
                    $layananService->sendMail($subject, 'layanan.notifications.mail', $data, $view, 'Melati ', $to);

                    $mst = LayananGroupSolver::where('LayananId', $layanan->Id)->pluck('MstGroupSolverId')->toArray();
                    $ketuaGroup = Pegawai::whereIn('KdUnitOrg',$mst)->where('StsPensiun',0)->where('JnsJabatanCur',1)->pluck('Nip')->toArray();
                    $ketuaGroup = User::whereIn('NIP', $ketuaGroup)->get();
                    foreach ($ketuaGroup as $value) {
                        $data['user'] = $value;
                        $view = view('layanan.notifications.wa', compact('data'))->render();
                        $layananService->sendMail($subject, 'layanan.notifications.mail', $data, $view, 'Melati ', $value);
                    }
                } else {
                    if ($layanan->Nip==userNip()) {
                        $solverLayanan = LayananSolver::where('LayananId',$layanan->Id)->pluck('Nip')->toArray();
                        $solver = User::whereIn('Nip',$solverLayanan)->get();
                        foreach ($solver as $item) {
                            $layananService->sendMail($subject, 'layanan.notifications.mail', $data, $view, 'Melati ', $item);
                        }
                    } elseif (auth()->user()->hasRole(['Solver'])) {
                        $layananService->sendMail($subject, 'layanan.notifications.mail', $data, $view, 'Melati ', $to);
                    }
                }
                $input['CreatedAt'] = Carbon::now();
                $input['Nip'] = $layanan->Nip;
                $input['Jenis'] = 'Confirm';
                $input['LayananId'] = $layanan->Id;
                LayananNotifikasi::create($input);
            }
            $layanan->save();
            if($layanan->AllSolver==null&&$request->StatusLayanan==4){
                $inputSolver['LayananId'] = $request->LayananId;
                $inputSolver['Nip'] = auth()->user()->NIP;
                $inputSolver['CreatedAt'] = Carbon::now();
                $inputSolver['CreatedBy'] = auth()->user()->NIP;
                LayananSolver::create($inputSolver);
                DB::statement("Update Layanan set [AllSolver]= [dbo].Func_getAllSolver('{$request->LayananId}') WHERE Id='{$request->LayananId}'");
            }
            DB::commit();
            $response['success'] = true;
            $response['message'] = 'Berhasil Tambah Tindak Lanjut';
        } catch (\Exception $e) {
            $response['success'] = false;
            $response['message'] = $e->getMessage();
            \Log::error($e->getTraceAsString());
        }
        return response()->json($response);
    }
    public function selesaikan(Request $request,Layanan $layanan)
    {
        $layanan->TglClosed = Carbon::now();
        $layanan->NipOperatorClosed = auth()->user()->NIP;
        $layanan->StatusLayanan = 5;
        $layanan->save();

        $inputTL['Id'] = uuid();
        $inputTL['LayananId'] = $layanan->Id;
        $inputTL['StatusLayanan'] = 5;
        $inputTL['Nip'] = auth()->user()->NIP;
        $inputTL['CreatedAt'] = Carbon::now();
        $inputTL['CreatedBy'] = auth()->user()->NIP;
        LayananTL::create($inputTL);
        $layanan->UpdatedAt = Carbon::now();
        $layanan->UpdatedBy = auth()->user()->NIP;
        $layanan->save();

        $inputLog['LayananId'] = $layanan->Id;
        $inputLog['LayananTLId'] = $inputTL['Id'];
        $inputLog['Nip'] = auth()->user()->NIP;
        $inputLog['RefStatusLayananIdAwal'] = $layanan->StatusLayanan ?? null;
        $inputLog['RefStatusLayananIdAkhir'] = 5;
        $inputLog['CreatedAt'] = Carbon::now();
        $inputLog['CreatedBy'] = auth()->user()->NIP;
        $inputLog['Keterangan'] = 'Update Status';
        LayananLog::create($inputLog);

        $response['success'] = true;
        $response['message'] = 'Berhasil Menyelesaikan Permintaan Layanan';
        return response()->json($response);
    }
    public function inputLayananLog($request, $LayananTLId, $layanan, $layanan_tl, $keterangan,$statusLayananAwal)
    {
        $inputLog['LayananId'] = $request->LayananId ?? $layanan->layanan_tl;
        $inputLog['LayananTLId'] = $LayananTLId;
        $inputLog['Nip'] = auth()->user()->NIP;
        $inputLog['Keterangan'] = $keterangan;
        $inputLog['RefStatusLayananIdAwal'] = $statusLayananAwal ?? null;
        $inputLog['RefStatusLayananIdAkhir'] = $request->StatusLayanan ?? null;
        $inputLog['CreatedAt'] = Carbon::now();
        $inputLog['CreatedBy'] = auth()->user()->NIP;
        LayananLog::create($inputLog);
    }
    public function update(Request $request, LayananTL $layanan_tl)
    {
        try {
            DB::beginTransaction();
            $inputTL['LayananId'] = $request->LayananId;
            $inputTL['Keterangan'] = $request->Keterangan;
            $inputTL['StatusLayanan'] = $request->StatusLayanan;
            $inputTL['UpdatedAt'] = Carbon::now();
            $inputTL['UpdatedBy'] = auth()->user()->NIP;
            LayananTL::where('Id', $layanan_tl->Id)->update($inputTL);
            $layanan = Layanan::find($request->LayananId);
            $layanan->UpdatedAt = Carbon::now();
            $layanan->StatusLayanan = $request->StatusLayanan;
            $layanan->UpdatedBy = auth()->user()->NIP;
            $statusLayananAwal =  $layanan->StatusLayanan;
            if ($request->StatusLayanan && $layanan_tl->StatusLayanan != $request->StatusLayanan) {
                $layanan->StatusLayanan = $request->StatusLayanan;
            }
            $layanan->save();
            if ($request->persediaanId) {
                for ($i = 0; $i < count($request->persediaanId); $i++) {
                    LayananPersediaan::updateOrCreate(
                        [
                            'Id' => $request->persediaanId[$i]
                        ],
                        [
                            'MstPersediaanId' => $request->mstPersediaanId[$i],
                            'Qty' => $request->qtyPersediaan[$i],
                            'Keterangan' => $request->keteranganPersediaan[$i],
                            'LayananTLId' => $layanan_tl->Id,
                            'LayananId' => $request->LayananId,
                        ]
                    );
                }
                $this->inputLayananLog($request, $layanan_tl->Id, $layanan, null, 'Update Persedian Layanan',$layanan->StatusLayanan);
            }

            if ($request->layananAsetId) {
                for ($i = 0; $i < count($request->layananAsetId); $i++) {
                    $data =
                        [
                            'AsetLayananId' => $request->asetId[$i],
                            'Keterangan' => $request->keteranganAset[$i],
                            'Fisik' => $request->fisik[$i],
                            'Kelengkapan' => $request->kelengkapan[$i],
                            'Data' => $request->data[$i],
                            'NoBox' => $request->noBox[$i],
                            'LayananTLId' => $layanan_tl->Id,
                            'LayananId' => $request->LayananId,
                        ];
                    if ($request->isAsetSMA[$i])
                        $data =
                            [
                                'AsetSMAId' => $request->asetId[$i],
                                'Keterangan' => $request->keteranganAset[$i],
                                'Fisik' => $request->fisik[$i],
                                'Kelengkapan' => $request->kelengkapan[$i],
                                'Data' => $request->data[$i],
                                'NoBox' => $request->noBox[$i],
                                'LayananTLId' => $layanan_tl->Id,
                                'LayananId' => $request->LayananId,
                            ];
                    LayananAset::updateOrCreate(
                        [
                            'Id' => $request->layananAsetId[$i]
                        ],
                        $data
                    );
                }
                $this->inputLayananLog($request, $layanan_tl->Id, $layanan, null, 'Update Aset Layanan',$layanan->StatusLayanan);
            }

            // IsPeminjaman
            if ($request->asetLayananId) {
                $this->insertOrUpdatePeminjamanDetail($request,$layanan_tl->Id);
            }
            inputFiles($request, $layanan_tl->Id, 'FileAttachment', null, 'LayananTL');
            if ($request->Keterangan) {
                $this->inputLayananLog($request, $layanan_tl->Id, $layanan, $layanan_tl, 'Updates TL',$statusLayananAwal);
            }
            if ($request->StatusLayanan && $layanan_tl->StatusLayanan != $request->StatusLayanan) {
                $this->inputLayananLog($request, $layanan_tl->Id, $layanan, $layanan_tl, 'Update Status',$statusLayananAwal);
            }
            logActivity('default', 'Layanan')->log("Update Tindaklanjut {$layanan_tl->Id} Layanan {$request->LayananId}");
            DB::commit();
            $response['success'] = true;
            $response['message'] = 'Berhasil Edit Tindak Lanjut';
        } catch (\Exception $e) {
            $response['success'] = false;
            $response['message'] = $e->getMessage();
            \Log::error($e->getTraceAsString());
        }
        return response()->json($response);
    }
    public function destroy(LayananTL $layanan_tl)
    {
        $response = self::$response;
        $delete = false;
        try {
            $layanan_tl->DeletedAt = Carbon::now()->format('Y-m-d H:i:s');
            $layanan_tl->DeletedBy = auth()->user()->NIP;
            $delete = $layanan_tl->save();
            $layanan = Layanan::find($layanan_tl->LayananId);
            $this->inputLayananLog(null, $layanan_tl->Id, $layanan, $layanan_tl, 'Updates TL',$layanan->StatusLayanan);
            $response['success'] = $delete;
            $response['message'] = $delete ? 'Berhasil Hapus Data' : 'Gagal Hapus Data';
            logActivity('default', 'Layanan')->log("Delete Tindaklanjut {$layanan_tl->Id} Layanan {$layanan_tl->LayananId}");

            return response()->json($response);
        } catch (\Exception $e) {
            $response['success'] = false;
            $response['message'] = $e->getMessage();
            return response()->json($response);
        }
    }

    public function destroyPersediaan(LayananPersediaan $persediaan)
    {
        $response = self::$response;
        $delete = false;
        try {
            $persediaan->DeletedAt = Carbon::now()->format('Y-m-d H:i:s');
            $persediaan->DeletedBy = auth()->user()->NIP;
            $delete = $persediaan->save();
            $layanan = Layanan::find($persediaan->LayananId);
            $this->inputLayananLog(null, $persediaan->Id, $layanan, $persediaan, 'Updates TL',$layanan->StatusLayanan);
            $response['success'] = $delete;
            $response['message'] = $delete ? 'Berhasil Hapus Data' : 'Gagal Hapus Data';
            logActivity('default', 'Layanan')->log("Delete Persediaan Layanan {$persediaan->Id}");

            return response()->json($response);
        } catch (\Exception $e) {
            $response['success'] = false;
            $response['message'] = $e->getMessage();
            return response()->json($response);
        }
    }
    public function destroyAset(LayananAset $aset)
    {
        $response = self::$response;
        $delete = false;
        try {
            $aset->DeletedAt = Carbon::now()->format('Y-m-d H:i:s');
            $aset->DeletedBy = auth()->user()->NIP;
            $delete = $aset->save();
            $layanan = Layanan::find($aset->LayananId);
            $this->inputLayananLog(null, $aset->Id, $layanan, $aset, 'Updates TL',$layanan->StatusLayanan);
            $response['success'] = $delete;
            $response['message'] = $delete ? 'Berhasil Hapus Data' : 'Gagal Hapus Data';
            logActivity('default', 'Layanan')->log("Delete Aset Layanan {$aset->Id}");

            return response()->json($response);
        } catch (\Exception $e) {
            $response['success'] = false;
            $response['message'] = $e->getMessage();
            return response()->json($response);
        }
    }
    public function destroyPeminjamanDetail(PeminjamanDetail $peminjamanDetail)
    {
        $response = self::$response;
        $delete = false;
        try {
            $peminjamanDetail->DeletedAt = Carbon::now()->format('Y-m-d H:i:s');
            $peminjamanDetail->DeletedBy = auth()->user()->NIP;
            $delete = $peminjamanDetail->save();
            $layanan = Layanan::find($peminjamanDetail->peminjaman->LayananId);
            $this->inputLayananLog(null, $peminjamanDetail->peminjaman->LayananTLId, $layanan, $peminjamanDetail, 'Updates TL',$layanan->StatusLayanan);
            $response['success'] = $delete;
            $response['message'] = $delete ? 'Berhasil Hapus Data' : 'Gagal Hapus Data';
            logActivity('default', 'Layanan')->log("Delete Peminjaman Detail {$peminjamanDetail->Id}");

            return response()->json($response);
        } catch (\Exception $e) {
            $response['success'] = false;
            $response['message'] = $e->getMessage();
            return response()->json($response);
        }
    }
    public function show(LayananTL $layanan_tl)
    {
        $response['data'] = $layanan_tl;
        $response['success'] = true;
        return response()->json($response);
    }
}
