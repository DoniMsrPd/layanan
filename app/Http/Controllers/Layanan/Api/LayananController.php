<?php

namespace App\Http\Controllers\Layanan\Api;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Layanan\LayananController as LayananLayananController;
use App\Http\Resources\LayananIndexResource;
use App\Http\Resources\LayananResource;
use App\Http\Resources\PaginatedCollection;
use App\Models\Layanan\Layanan;
use App\Models\Layanan\LayananKategori;
use App\Models\Layanan\LayananNotifikasi;
use App\Models\Layanan\RefJenisLayanan;
use App\Models\Layanan\RefPrioritas;
use App\Models\Layanan\RefStatusLayanan;
use App\Models\User;
use App\Services\LayananService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class LayananController extends Controller
{
    protected $layananCtr;

    public function __construct()
    {
        $this->layananCtr = app(LayananLayananController::class);
    }
    public function index(Request $request, LayananService $layananService)
    {
        $layanan = $layananService->getAllLayanan()->filtered()->filtered2()->without(['pelapor','status','layananKategori'])
            ->with(['tl' => function ($query) {
                $query->select('CreatedAt', 'Keterangan','LayananId')->without(['layananPersediaan','layananAset','layananPeminjaman']);
            }]);

            $layanan->where(function($q){
                $q->whereBetween('TglLayanan', [request()->tglStart ?? date('Y') . '-01-01' . ' 00:00:00', request()->tglEnd ?? date('Y-m-d') . ' 23:59:59'])->orWhereNull('TglLayanan');
            });
            if ($request->newData) {
                $layanan->whereNull('NoTicket');
            }else if ($request->allData) {
                $layanan->whereNotNull('NoTicket');
            }
            $layanan = $layanan->orderBy('UpdatedAt', 'DESC')->paginate(10);
        return response(['success' => true, 'data'=>new PaginatedCollection($layanan,LayananIndexResource::class) ], Response::HTTP_OK);
    }

    function inputLayanan($request, $update = false)
    {
        $inputLayanan = [];
        if (!$request->pending) {

            $inputLayanan = [
                'Nip' => $request->Nip,
                'KdUnitOrg' => $request->KdUnitOrg,
                'NmUnitOrg' => $request->NmUnitOrg,
                'NmUnitOrgInduk' => $request->NmUnitOrgInduk,
                'NmPeg' => $request->NmPeg,
                'NipLayanan' => $request->pegawaiLain == null ? $request->Nip :  $request->NipLayanan,
                'KdUnitOrgLayanan' => $request->pegawaiLain == null ? $request->KdUnitOrg : $request->KdUnitOrgLayanan,
                'PermintaanLayanan' => $request->PermintaanLayanan,
                'NomorKontak' => $request->NomorKontak,
                // 'TglLayanan' => date('Y-m-d'),
            ];
        }

        return $inputLayanan;
    }
    public function store(Request $request, LayananService $layananService)
    {
        $this->authorize('layanan.create');
        DB::beginTransaction();
        try {
            $validator = Validator::make($request->all(), [
                'Nip' => 'required',
                'KdUnitOrg' => 'required',
                'NmUnitOrg' => 'required',
                'NmUnitOrgInduk' => 'required',
                'NmPeg' => 'required',
                'NomorKontak' =>'required',
                'PermintaanLayanan' => 'required',
                'FileAttachment.*' => 'mimes:pdf,rar,jpg,jpeg,png,bmp,gif,svg,docx,doc,xlsx,xls,ppt,pptx'
            ]);
            if ($validator->fails()) {
                return response()->json(['success' => false, "message" => $validator->errors()->first()],Response::HTTP_UNPROCESSABLE_ENTITY);
            }
            $inputLayanan = $this->inputLayanan($request);
            $inputLayanan['Id'] = uuid();
            $inputLayanan['NoTicketRandom'] = substr($inputLayanan['Id'], 0, 5);
            $inputLayanan['StatusLayanan'] = 2;
            $inputLayanan['CreatedAt'] = Carbon::now();
            $inputLayanan['CreatedBy'] = auth()->user()->NIP;
            $inputLayanan['UpdatedAt'] = Carbon::now();
            $inputLayanan['UpdatedBy'] = auth()->user()->NIP;
            $inputLayanan['KdUnitOrgOwnerLayanan'] = kdUnitOrgOwner();
            $layanan = Layanan::create($inputLayanan);
            inputFiles($request, $inputLayanan['Id']);
            if ($request->Kategori) {
                for ($i = 0; $i < count($request->Kategori); $i++) {
                    $inputKategori['LayananId'] = $inputLayanan['Id'];
                    $inputKategori['MstKategoriId'] = $request->Kategori[$i];
                    $inputKategori['CreatedAt'] = Carbon::now();
                    $inputKategori['CreatedBy'] = auth()->user()->NIP;
                    LayananKategori::create($inputKategori);
                }
            }
            logActivity('default', 'Layanan')->log("Create Permintaan Layanan " . $inputLayanan['Id']);
            $subject = "Layanan {$layanan->NoTicketRandom} :: New";
            $to = User::where('NIP', auth()->user()->NIP)->first();
            $data = [
                'user' => $to,
                'layanan' => $layanan,
                'url' => route('layanan.eskalasi', $inputLayanan['Id']),
                'message' => 'Telah kami terima dan akan segera kami proses. Silahkan klik tombol dibawah ini untuk melihat detil layanan dan memantau proses penyelesaian layanan'
            ];
            $view = view('layanan.notifications.wa', compact('data'))->render();
            $layananService->sendMail($subject, 'layanan.notifications.mail', $data, $view, 'Melati ', $to);
            $input['CreatedAt'] = Carbon::now();
            $input['Nip'] = auth()->user()->NIP;
            $input['Jenis'] = 'New';
            $input['LayananId'] = $inputLayanan['Id'];
            LayananNotifikasi::create($input);
            DB::commit();
            return response(['success' => true,'message' => 'Tambah Permintaan Layanan Berhasil', "data" => ['NoTicketRandom' => $layanan->NoTicketRandom,'PermintaanLayanan'=>$layanan->PermintaanLayanan] ], Response::HTTP_OK);
        } catch (\Exception $e) {
            DB::rollback();
            $msg = $e->getMessage();
            \Log::error($e->getTraceAsString());
            return response(['success' => true,'message' => $e->getMessage()],500);
        }
    }
    public function destroy(Layanan $layanan)
    {
        $this->authorize('layanan.delete');
        $delete = false;

        try {
            $layanan->DeletedAt = Carbon::now()->format('Y-m-d H:i:s');
            $layanan->DeletedBy = auth()->user()->NIP;
            $delete = $layanan->save();
            $response['success'] = $delete;
            $response['message'] = $delete ? 'Berhasil Hapus Data' : 'Gagal Hapus Data';

            logActivity('default', 'Layanan')->log("Delete Layanan {$layanan->Id}");
            return response()->json($response);
        } catch (\Exception $e) {
            $response['success'] = false;
            $response['message'] = $e->getMessage();
            return response()->json($response);
        }
    }
    public function show($id)
    {
        $layanan = Layanan::find($id);
        return response(['success' => true, "data" => new LayananResource($layanan) ], Response::HTTP_OK);
    }
    public function destroyKategori(LayananKategori $kategori)
    {
        $this->authorize('layanan.delete');
        $delete = false;

        try {
            $delete =  $kategori->delete();
            $response['success'] = $delete;
            $response['message'] = $delete ? 'Berhasil Hapus Data' : 'Gagal Hapus Data';

            logActivity('default', 'Layanan')->log("Delete Layanan Kategori {$kategori->Id}");
            return response()->json($response);
        } catch (\Exception $e) {
            $response['success'] = false;
            $response['message'] = $e->getMessage();
            return response()->json($response);
        }
    }
    public function update(Request $request, Layanan $layanan)
    {
        $this->authorize('layanan.update');
        try {
            DB::beginTransaction();
            $inputLayanan = $this->inputLayanan($request, true);
            $inputLayanan['UpdatedAt'] = Carbon::now();
            $inputLayanan['UpdatedBy'] = auth()->user()->NIP;
            if ($request->Kategori) {
                LayananKategori::where('LayananId', $layanan->Id)->whereNotIn('MstKategoriId', $request->Kategori)->delete();
                for ($i = 0; $i < count($request->Kategori); $i++) {
                    $cek = LayananKategori::where('LayananId', $layanan->Id)->where('MstKategoriId', $request->Kategori[$i])->first();
                    if (!$cek) {
                        $inputKategori['LayananId'] = $layanan->Id;
                        $inputKategori['MstKategoriId'] = $request->Kategori[$i];
                        $inputKategori['CreatedAt'] = Carbon::now();
                        $inputKategori['CreatedBy'] = auth()->user()->NIP;
                        LayananKategori::create($inputKategori);
                    }
                }
            }
            if ($request->eskalasi) {
                $inputLayanan['ServiceCatalogId'] = $request->ServiceCatalogId;
                $inputLayanan['ServiceCatalogNama'] = $request->ServiceCatalogNama;
                $inputLayanan['ServiceCatalogKode'] = $request->ServiceCatalogKode;
                $inputLayanan['ServiceCatalogDetailId'] = $request->ServiceCatalogDetailId;
                // $inputLayanan['NotaDinas'] = $request->NotaDinas;
                $inputLayanan['PrioritasLayanan'] = $request->Prioritas;
                // $inputLayanan['NotifikasiEmail'] = $request->NotifikasiEmail;
                $inputLayanan['KeteranganLayanan'] = $request->KeteranganLayanan;
                $layanan->NipOperatorOpen == null ? $inputLayanan['NipOperatorOpen'] = auth()->user()->NIP:'';
                if (!$layanan->JenisLayanan) {
                    $inputLayanan['JenisLayanan'] = $request->JenisLayanan;
                    $inputLayanan['TglLayanan'] = date('Y-m-d');
                    $inputLayanan['NoTicketTahun'] = date('Y');
                    $inputLayanan['NoTicketBulan'] = date('m');
                    $inputLayanan['NoTicketAntrian'] = $this->layananCtr->getNoTiketAntrian($request->JenisLayanan);
                    $inputLayanan['NoTicket'] = $request->JenisLayanan . '-' . $inputLayanan['NoTicketTahun'] . $inputLayanan['NoTicketBulan'] . str_pad($inputLayanan['NoTicketAntrian'], 4, "0", STR_PAD_LEFT);
                    $inputLayanan['TglTicket'] = Carbon::now();
                }
            }
            logActivity('default', 'Layanan')->log("Update Permintaan Layanan " . $layanan->Id);
            inputFiles($request, $layanan->Id);
            Layanan::where('Id', $layanan->Id)->update($inputLayanan);
            DB::commit();
            return response(['success' => true,'message' => 'Update Permintaan Layanan Berhasil' ], Response::HTTP_OK);
        } catch (\Exception $e) {
            DB::rollback();
            \Log::error($e->getTraceAsString());
            return response(['success' => true,'message' => $e->getMessage()],500);
        }
    }
    public function statusLayanan()
    {
        $data = RefStatusLayanan::where('KdUnitOrgOwnerLayanan',kdUnitOrgOwner())->select('Id','Nama')->get();
        return response(['success' => true, "data" => $data ], Response::HTTP_OK);
    }
    function prioritasLayanan() {
        $data =  RefPrioritas::select('Id')->where('KdUnitOrgOwnerLayanan',kdUnitOrgOwner())->orderBy('No')->get();
        return response(['success' => true, "data" => $data ], Response::HTTP_OK);
    }
    function jenisLayanan() {
        $data =  RefJenisLayanan::select('Id','Nama')->orderBy('No')->get();
        return response(['success' => true, "data" => $data ], Response::HTTP_OK);
    }
}