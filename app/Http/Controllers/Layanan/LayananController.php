<?php

namespace App\Http\Controllers\Layanan;

use Excel;
use App\Http\Controllers\Controller;
use App\Models\Layanan\Layanan;
use App\Models\Layanan\LayananGroupSolver;
use App\Models\Layanan\LayananKategori;
use App\Models\Layanan\LayananNotifikasi;
use App\Models\Layanan\LayananSolver;
use App\Models\Layanan\MstUnitOrgLayananOwner;
use App\Models\Layanan\RefJenisLayanan;
use App\Models\Layanan\RefPrioritas;
use App\Models\Layanan\RefStatusLayanan;
use App\Models\Setting\JnsAset;
use App\Models\Setting\Kategori;
use App\Models\Setting\MstTematik;
use App\Models\Setting\ServiceCatalog;
use App\Models\Setting\Solver;
use App\Models\System\MelatiFile;
use App\Models\User;
use App\Services\LayananService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;

class LayananController extends Controller
{

    private static $response = [
        'success' => false,
        'data'    => null,
        'message' => null
    ];
    private $service;
    public function __construct(LayananService $service)
    {
        $this->service = $service;
    }
    public function destroyFile(MelatiFile $file)
    {
        $response = self::$response;
        $delete = false;
        try {
            $file->deletedAt = Carbon::now()->format('Y-m-d H:i:s');
            $file->deletedBy = auth()->user()->NIP;
            $delete = $file->save();
            $response['success'] = $delete;
            $response['message'] = $delete ? 'Berhasil Hapus File' : 'Gagal Hapus File';
        } catch (\Exception $e) {
            $response['success'] = false;
            $response['message'] = $e->getMessage();
        }
        return response()->json($response);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, LayananService $layananService)
    {
        $this->authorize('layanan.read');
        logActivity('default', 'Layanan')->log("View Layanan");
        if (!pegawaiBiasa()) {
            // $solver =  DB::select("select distinct MstSolver.[Nip], NmPeg from [MstSolver] join SpgDataCurrent on SpgDataCurrent.Nip = MstSolver.Nip where [dbo].[Func_getKdUnitOrgOwnerLayanan] (MstSolver.[Nip] ) = [dbo].[Func_getKdUnitOrgOwnerLayanan] ( " . kdUnitOrgOwner() . " )");
            $solver = Solver::solver()->get()->toArray();
            array_unshift($solver, [
                'Nip' => 'Kosong',
                'NmPeg' => 'Belum di-Assign ke Solver'
            ]);
            $groupSolver =  DB::select("select Id, Kode from MstGroupSolver where KdUnitOrgOwnerLayanan = '" . kdUnitOrgOwner() . "'  order By Id");
            array_unshift($groupSolver, (object)[
                'Id' => 'Kosong',
                'Kode' => 'Belum di-Assign ke Group Solver'
            ]);
            $statusLayanan = RefStatusLayanan::where('KdUnitOrgOwnerLayanan', kdUnitOrgOwner());
            if (kdUnitOrgOwner() != '100205000000' && auth()->user()->hasRole(['Solver']) && !auth()->user()->hasRole(['Operator', 'Admin Proses Bisnis', 'Admin Probis Layanan'])) {
                $statusLayanan = $statusLayanan->whereNotIn('Id', ['1']);
            }
            $data = (object) [
                'groupSolver' => $groupSolver,
                'solver' => $solver,
                'statusLayanan' => $statusLayanan->get(),
                'prioritasLayanan' => RefPrioritas::where('KdUnitOrgOwnerLayanan', kdUnitOrgOwner())->orderBy('No')->get(),
                'serviceCatalog' => ServiceCatalog::where('KdUnitOrgOwnerLayanan', kdUnitOrgOwner())->whereNull('DeletedAt')->whereRaw('TglEnd is null or TglEnd > getdate()')->orderBy('Kode')->get(),
                'tematik' => MstTematik::whereNull('DeletedAt')->get(),
                'bukanTI' => kdUnitOrgOwner() != '100205000000',
            ];
            $data->MstUnitOrgLayananOwner = MstUnitOrgLayananOwner::all();
        }
        if (isMobile() && (isTablet() == null)) {
            $layanan = $layananService->getAllLayanan()->filtered()->filtered2()->with(['operatorOpen'])->where(function ($q) {
                $q->whereBetween('TglLayanan', [request()->tglStart ?? date('Y') . '-01-01' . ' 00:00:00', request()->tglEnd ?? date('Y-m-d') . ' 23:59:59'])->orWhereNull('TglLayanan');
            })->orderBy('UpdatedAt', 'DESC')->paginate(10);
            $data->layanan = $layanan;
            if ($request->ajax()) {
                return view('layanan.layanan.mobile.indexAjax', compact('data'));
            }
            return view('layanan.layanan.mobile.index', compact('data'));
        }
        if (pegawaiBiasa()) {
            $kdunit = $request->KdUnitOrgOwnerLayanan ?? '100205000000';
            $data = (object) [
                'statusLayanan' => RefStatusLayanan::where('KdUnitOrgOwnerLayanan', $kdunit)->orderBy('No', 'asc')->get(),
            ];
            $data->MstUnitOrgLayananOwner = MstUnitOrgLayananOwner::all();
            $layanan = $layananService->getAllLayanan()->filtered()->filtered2()->with(['operatorOpen', 'files', 'filesOld', 'owner'])->where(function ($q) {
                $q->whereBetween('TglLayanan', [request()->tglStart ?? date('Y') . '-01-01' . ' 00:00:00', request()->tglEnd ?? date('Y-m-d') . ' 23:59:59'])->orWhereNull('TglLayanan');
            })->orderBy('UpdatedAt', 'DESC')->paginate(10);

            $data->layanan = $layanan;
            if ($request->ajax()) {
                return view('layanan.layanan.indexPegawaiAjax', compact('data'));
            }
            return view('layanan.layanan.index-pegawai', compact('data'));
        }
        return view('layanan.layanan.index', compact('data'));
    }
    public function index_baru(Request $request)
    {
        $this->authorize('layanan.read');
        logActivity('default', 'Permintaan Layanan Baru ')->log("View Layanan Baru");
        return view('layanan.layanan.index_baru');
    }
    public function dataTables(Request $request, LayananService $layananService)
    {
        $data = $layananService->getAllLayanan()->with(['operatorOpen'])->filtered2();
        if (request()->tglStart) {
            $data->where(function ($q) {
                $tglAwal = date('Y-m-d', strtotime(request()->tglStart ?? date('Y-m-d')));
                $tglAkhir = date('Y-m-d', strtotime(request()->tglEnd ?? date('Y-m-d')));
                $q->whereBetween('TglLayanan', [$tglAwal ?? date('Y') . '-01-01' . ' 00:00:00', $tglAkhir ?? date('Y-m-d') . ' 23:59:59'])->orWhereNull('TglLayanan');
            });
            $data->where(function ($q) {
                $tglAwal = date('Y-m-d', strtotime(request()->tglStart ?? date('Y-m-d'))) . ' 00:00:00';
                $tglAkhir = date('Y-m-d', strtotime(request()->tglEnd ?? date('Y-m-d'))) . ' 23:59:59';
                $q->whereBetween('CreatedAt', [$tglAwal ?? date('Y') . '-01-01' . ' 00:00:00', $tglAkhir ?? date('Y-m-d') . ' 23:59:59']);
            });
        }
        if ($request->Nip)
            $data->where('Nip', $request->Nip);
        if ($request->Exclude) {

            $data->where('Layanan.Id', '!=', $request->Exclude)->whereNotNull('NoTicket');
        }
        if ($request->layananBaru) {
            $data = Layanan::whereNull('DeletedAt ')->whereNull('NoTicket');
            if (pegawaiBiasa())
                $data = $layananService->getAllLayanan()->where('StatusLayanan', 6);
        }
        if ($request->pending == 1) {
            $data->whereNull('ServiceCatalogDetailId');
        }
        if ($request->sla) {
            $data->selectRaw("Layanan.*,RefStatusLayanan.Nama AS NamaStatusLayanan,NormaWaktu,Limit,Datediff(hour, [tglticket], CASE WHEN [layanan].statuslayanan IN ( 5, 6, 7 ) THEN isnull([Layanan].TglSolved,[Layanan].[TglTicket]) ELSE Getdate() END) LamaJamLayanan");
            $data->leftJoin('ServiceCatalogDetail', 'Layanan.ServiceCatalogDetailId', '=', 'ServiceCatalogDetail.Id');
            $data->whereNotNull('NoTicket')->whereNotNull('ServiceCatalogDetailId');
            if (count($request->sla) == 1) {
                if ($request->sla[0] == 'melewati')
                    $data->whereRaw("isnull(Limit,0)  < Datediff(hour, [tglticket], CASE WHEN [layanan].statuslayanan IN ( 5, 6, 7 ) THEN isnull([Layanan].TglSolved,[Layanan].[TglTicket]) ELSE Getdate() END)");
                if ($request->sla[0] == 'tidak_melewati')
                    $data->whereRaw("isnull(Limit,0)  >= Datediff(hour, [tglticket], CASE WHEN [layanan].statuslayanan IN ( 5, 6, 7 ) THEN isnull([Layanan].TglSolved,[Layanan].[TglTicket]) ELSE Getdate() END)");
                if ($request->sla[0] == 'mendekati_deadline')
                    $data->whereIn('StatusLayanan', [2, 3])->whereIn('Layanan.Id', function ($query) {
                        $query->select('Id')
                            ->from('vRptSLADeadline');
                    });
            }
        }
        if (kdUnitOrgOwner() != '100205000000' && auth()->user()->hasRole(['Solver']) && !auth()->user()->hasRole(['Operator', 'Admin Proses Bisnis', 'Admin Probis Layanan'])) {
            $data->where('StatusLayanan', '<>', '1');
        }
        return DataTables::of($data)->order(function ($query) {
            if (request()->order[0]['column'] == 1) {
                $query->orderBy('Layanan.TglLayanan', request()->order[0]['dir']);
            } else {

                if (request()->layananBaru) {
                    $query->orderBy('Layanan.UpdatedAt', 'ASC');
                } else {
                    $query->orderBy('Layanan.CreatedAt', 'DESC');
                }
            }
        })
            ->filter(function ($query) {
                $q = request()->search['value'] ?? strtolower(request()->q);
                if ($q) {
                    $query->whereRaw("([Layanan].[Id] LIKE '%" . strtolower($q) . "%' or  [Layanan].[NoTicket] LIKE '%" . strtolower($q) . "%' or [Layanan].[ServiceCatalogKode] LIKE '%" . strtolower($q) . "%' or [Layanan].[Nip] LIKE '%" . strtolower($q) . "%' or [Layanan].[KdUnitOrg] LIKE '%" . strtolower($q) . "%' or [Layanan].[PermintaanLayanan] LIKE '%" . strtolower($q) . "%' or [Layanan].[NmPeg] LIKE '%" . strtolower($q) . "%' or [Layanan].[NoTicketRandom] LIKE '%" . strtolower($q) . "%' or [Layanan].[NmUnitOrg] LIKE '%" . strtolower($q) . "%' or [Layanan].[NmUnitOrgInduk] LIKE '%" . strtolower($q) . "%')");
                }
            })
            ->editColumn('PermintaanLayanan', function ($data) {
                return strip_tags(nl2br($data->PermintaanLayanan), "<p><br>");
            })->addColumn('PermintaanLayanan2', function ($data) {
                return strip_tags(nl2br($data->PermintaanLayanan), "<p><br>");
            })
            ->editColumn('TglLayanan', function ($data) {
                $sla = '';
                if (request()->sla) {
                    $color = 'text-success';
                    if ($data->Limit < $data->LamaJamLayanan)
                        $color = 'text-danger';
                    $sla = "<br><span class='{$color}'>{$data->NormaWaktu}</span>";
                }
                return pegawaiBiasa() ? ToDmyHi($data->CreatedAt) . $sla  : ToDmy($data->TglLayanan) . $sla;
            })->editColumn('StatusLayanan', function ($data) {
                $statusLayanan = $data->NamaStatusLayanan ?? '-';
                if (pegawaiBiasa() && $data->StatusLayanan == 6)
                    $statusLayanan = "<span class='blink_me'>{$data->NamaStatusLayanan} <i class='os-icon os-icon-mail-12'></i></span>";
                return $statusLayanan;
            })->editColumn('NoTicket', function ($data) {

                if (pegawaiBiasa()) {
                    $status = [3];
                    if ($data->NoTicket)
                        $status = [3, 4];

                    $refStatusLayanan = RefStatusLayanan::whereIn('Id', $status)->orderBy('No')->get();
                } elseif ($data->KdUnitOrgOwnerLayanan != '100205000000') {
                    $arr = [];
                    if (auth()->user()->hasAllRoles(['Solver', 'Pejabat Struktural'])) {
                        $arr = [3, 6, 7, 4];
                    } elseif (auth()->user()->hasAllRoles(['Solver', 'Operator'])) {
                        $arr = [2, 3, 6, 5];
                    } elseif (auth()->user()->hasAllRoles(['Solver'])) {
                        $arr = [3, 6];
                    }
                    $refStatusLayanan = RefStatusLayanan::where('KdUnitOrgOwnerLayanan', $data->KdUnitOrgOwnerLayanan)->whereIn('Id', $arr)->orderBy('No')->get();
                } elseif (auth()->user()->hasRole(['Operator', 'SuperUser', 'Admin Proses Bisnis', 'Admin Probis Layanan'])) {
                    $refStatusLayanan = RefStatusLayanan::where('KdUnitOrgOwnerLayanan', $data->KdUnitOrgOwnerLayanan)->orderBy('No')->get();
                    if (!$data->NoTicket)
                        $refStatusLayanan = RefStatusLayanan::whereNotIn('Id', [4, 5])->orderBy('No')->get();
                } else {
                    $refStatusLayanan = RefStatusLayanan::whereNotIn('Id', [4, 5, 7])->orderBy('No')->get();
                }
                $sts_link = '';
                $hidden = pegawaiBiasa() ? 'style="display: none"' : '';
                foreach ($refStatusLayanan as $key => $value) {
                    $sts_link .= '<li  ><a class="dropdown-item update-status-layanan" href="#" data-layanan_id="' . $data->Id . '" data-status_layanan="' . $value->Id . '">' . $value->Nama . '</a></li>';
                }
                $groupSolverBtn = '';
                $solverBtn = '';
                $editBtn = '';
                if (!$data->DeletedAt && (request()->user()->can('layanan.eskalasi.all') || auth()->user()->hasRole('Pejabat Struktural'))) {
                    if ($data->KdUnitOrgOwnerLayanan != '100205000000') {
                        if (auth()->user()->hasAllRoles(['Solver', 'Pejabat Struktural'])) {
                            $solverBtn = ' <a class="dropdown-item  lookup-solver" href="#" data-layanan_id="' . $data->Id . '">Solver Assign</a>';
                        } elseif (auth()->user()->hasAllRoles(['Solver', 'Operator'])) {
                            $groupSolverBtn = '<a class="dropdown-item  lookup-group-solver" href="#" data-layanan_id="' . $data->Id . '" >Group Assign</a>';
                        }
                    } else {
                        $solverBtn = ' <a class="dropdown-item  lookup-solver" href="#" data-layanan_id="' . $data->Id . '">Solver Assign</a>';
                        $groupSolverBtn = '<a class="dropdown-item  lookup-group-solver" href="#" data-layanan_id="' . $data->Id . '" >Group Assign</a>';
                    }
                }
                if (auth()->user()->can('layanan.eskalasi.all') && $data && !request()->edit && !pegawaiBiasa() && !$data->DeletedAt) {
                    if ($data->KdUnitOrgOwnerLayanan != '100205000000') {
                        if (auth()->user()->hasAllRoles(['Solver', 'Operator'])) {
                            $editBtn = '<a class="dropdown-item" href="' . route('layanan.eskalasi', $data->Id) . '?edit=1&kembali=layanan.index">Edit</a>';
                        }
                    } else {
                        $editBtn = '<a class="dropdown-item" href="' . route('layanan.eskalasi', $data->Id) . '?edit=1&kembali=layanan.index">Edit</a>';
                    }
                } elseif (auth()->user()->can('layanan.eskalasi.all') && $data && !request()->edit && !pegawaiBiasa() && !$data->DeletedAt) {
                    if ($data->KdUnitOrgOwnerLayanan != '100205000000') {
                        if (auth()->user()->hasAllRoles(['Solver', 'Operator'])) {
                            $editBtn = '<a class="dropdown-item" href="' . route('layanan.edit', $data->Id) . '?kembali=layanan.index">Edit</a>';
                        }
                    } else {
                        $editBtn = '<a class="dropdown-item" href="' . route('layanan.edit', $data->Id) . '?kembali=layanan.index">Edit</a>';
                    }
                }
                $button = '
                <div class="dropdown">
                  <a class="btn btn-primary dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                  <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-menu"><line x1="3" y1="12" x2="21" y2="12"></line><line x1="3" y1="6" x2="21" y2="6"></line><line x1="3" y1="18" x2="21" y2="18"></line></svg>
                                </a>
                  <ul class="dropdown-menu" aria-labelledby="dropdownMenuLink">
                  ' . $editBtn . $groupSolverBtn . $solverBtn . '

                    <li class="dropdown-submenu"  ' . $hidden . '><a class="dropdown-item dropdown-toggle" href="#">Status</a>
                      <ul class="dropdown-menu show">
                      ' . $sts_link . '
                      </ul>
                    </li>
                  </ul>
                </div>
                ';
                $id = $data->ParentId ?? $data->Id;
                $url = route('layanan.eskalasi', $id) . (request()->Nip ? '?merge=1' : '') . (request()->pending ? '?pending=1' : '');
                if ((pegawaiBiasa() || !auth()->user()->hasRole('Operator')) && !$data->NoTicket && !$data->ParentId) {
                    $url = route('layanan.edit', $id);
                }
                $res = '<a href="' . $url . '">' . $data->NoTicket . '<br>' . strtoupper($data->NoTicketRandom) . '</a><br><br>' . (!pegawaiBiasa() ? $button : '');
                return $res;
            })->addColumn('NoTiket', function ($data) {
                return $data->NoTicket;
            })->editColumn('UpdatedAt', function ($data) {
                return ToDmyHi($data->UpdatedAt);
            })->addColumn('action', function ($data) {
                $editButton = '';
                $deleteButton = '';
                $eskalasiButton = '';
                if (request()->user()->can('layanan.update'))
                    $editButton = '<a href="' . route('layanan.edit', $data->Id) . '" class="mb-2 mr-2 btn btn-warning btn-sm" title="Ubah" title-pos="up"><i class="icon-feather-edit-2"></i></a>';
                if (request()->user()->can('layanan.delete'))
                    $deleteButton = '<a data-id="' . $data->Id . ' " data-url="/layanan/' . $data->Id . ' " class="mb-2 mr-2 btn btn-danger btn-sm deleteData" data-title ="Data" title="Hapus" title-pos="up"><i class="icon-feather-trash-2"></i></a>';
                if (request()->user()->can('layanan.eskalasi'))
                    $eskalasiButton = '<a href="' . route('layanan.eskalasi', $data->Id) . '" class="mb-2 mr-2 btn btn-success btn-sm" title="Proses" title-pos="up"><i class="os-icon os-icon-arrow-right7"></i></a>';
                return '<span class="btn-group" role="group">' . $editButton . $eskalasiButton . $deleteButton . '</span>';
            })->rawColumns(['action', 'NoTicket', 'StatusLayanan', 'PermintaanLayanan', 'TglLayanan', 'PermintaanLayanan2'])->make(true);
    }
    public function create()
    {
        $this->authorize('layanan.create');
        $MstUnitOrgLayananOwner = MstUnitOrgLayananOwner::find(request()->KdUnitOrgOwnerLayanan);
        $data = (object) [
            'method' => 'POST',
            'action' => '/layanan',
            'title' => 'PERMINTAAN LAYANAN ' . strtoupper($MstUnitOrgLayananOwner->NmUnitOrgOwnerLayanan ?? ''),
            'layanan' => [],
            'eskalasi' => 0,
            'kembali' => route('layanan.index'),
            'kategori' => Kategori::whereNull('DeletedAt')->get(),
        ];
        return view('layanan.layanan.form', compact('data'));
    }
    public function createTiket()
    {
        $isOperator = (kdUnitOrgOwner() != '100205000000') && (auth()->user()->hasRole(['Operator']));
        $refJenisLayanan = RefJenisLayanan::orderBy('No')->get();
        if ($isOperator) {
            $refJenisLayanan = RefJenisLayanan::where('Id', 'R')->orderBy('No')->get();
        }
        $this->authorize('layanan.create-tiket');
        $data = (object) [
            'method' => 'POST',
            'action' => '/layanan-store-tiket',
            'title' => 'PEMBUATAN LAYANAN',
            'layanan' => [],
            'eskalasi' => 0,
            'refPrioritas' => RefPrioritas::where('KdUnitOrgOwnerLayanan', kdUnitOrgOwner())->orderBy('No')->get(),
            'refJenisLayanan' => $refJenisLayanan,
            'showForm' => true,
            'kategori' => [],
            'layananBaru' => [],
            'aset' => null
        ];
        return view('layanan.layanan.form-tiket', compact('data'));
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
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
            $inputLayanan = $this->inputLayanan($request);
            $inputLayanan['Id'] = uuid();
            $inputLayanan['NoTicketRandom'] = substr($inputLayanan['Id'], 0, 5);
            $inputLayanan['StatusLayanan'] = 1;
            $inputLayanan['KdUnitOrgOwnerLayanan'] = $request->KdUnitOrgOwnerLayanan;
            $inputLayanan['CreatedAt'] = Carbon::now();
            $inputLayanan['CreatedBy'] = auth()->user()->NIP;
            $inputLayanan['UpdatedAt'] = Carbon::now();
            $inputLayanan['UpdatedBy'] = auth()->user()->NIP;
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
            if ($request->KdUnitOrgOwnerLayanan != '100205000000') {
                $to = User::filterByRole($request->KdUnitOrgOwnerLayanan, ['Operator'])->first();
            }
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
            $msg = 'Permintaan Layanan Berhasil';
            $type = "success";
        } catch (\Exception $e) {
            DB::rollback();
            $type = "warning";
            $msg = $e->getMessage();
            \Log::error($e->getTraceAsString());
        }

        return redirect(url('layanan'))->with('flash_message', $msg)
            ->with('flash_type', $type);
    }
    public function getNoTiketAntrian($jenisLayanan)
    {
        $data = Layanan::selectRaw('isnull(max(NoTicketAntrian),0)+1 AS NoTiketAntrian')->where('NoTicketTahun', date('Y'))->where('NoTicketBulan', date('m'))->where('JenisLayanan', $jenisLayanan)->first();
        return $data->NoTiketAntrian;
    }
    public function storeTiket(Request $request, LayananService $layananService)
    {
        $this->authorize('layanan.create');
        DB::beginTransaction();
        try {
            $inputLayanan = $this->inputLayanan($request);
            // $inputLayanan['KdUnitOrgOwnerLayanan'] = $request->KdUnitOrgOwnerLayanan;
            $inputLayanan['StatusLayanan'] = 2;
            $inputLayanan['TglLayanan'] = date('Y-m-d');
            $inputLayanan['JenisLayanan'] = $request->JenisLayanan;
            $inputLayanan['ServiceCatalogId'] = $request->ServiceCatalogId;
            $inputLayanan['ServiceCatalogKode'] = $request->ServiceCatalogKode;
            $inputLayanan['ServiceCatalogNama'] = $request->ServiceCatalogNama;
            $inputLayanan['ServiceCatalogDetailId'] = $request->ServiceCatalogDetailId;
            $inputLayanan['NotaDinas'] = $request->NotaDinas;
            $inputLayanan['PrioritasLayanan'] = $request->Prioritas;
            $inputLayanan['NotifikasiEmail'] = $request->NotifikasiEmail;
            $inputLayanan['KeteranganLayanan'] = $request->KeteranganLayanan;
            $inputLayanan['NipOperatorOpen'] = auth()->user()->NIP;
            $inputLayanan['NoTicketTahun'] = date('Y');
            $inputLayanan['NoTicketBulan'] = date('m');
            $inputLayanan['NoTicketAntrian'] = $this->getNoTiketAntrian($request->JenisLayanan);
            $inputLayanan['NoTicket'] = $request->JenisLayanan . '-' . $inputLayanan['NoTicketTahun'] . $inputLayanan['NoTicketBulan'] . str_pad($inputLayanan['NoTicketAntrian'], 4, "0", STR_PAD_LEFT);
            $inputLayanan['TglTicket'] = Carbon::now();
            $inputLayanan['Id'] = uuid();
            $inputLayanan['NoTicketRandom'] = substr($inputLayanan['Id'], 0, 5);
            $inputLayanan['CreatedAt'] = Carbon::now();
            $inputLayanan['CreatedBy'] = auth()->user()->NIP;
            $inputLayanan['UpdatedAt'] = Carbon::now();
            $inputLayanan['UpdatedBy'] = auth()->user()->NIP;


            $layanan = Layanan::create($inputLayanan);

            if ($request->Kategori) {
                for ($i = 0; $i < count($request->Kategori); $i++) {
                    $inputKategori['LayananId'] = $inputLayanan['Id'];
                    $inputKategori['MstKategoriId'] = $request->Kategori[$i];
                    $inputKategori['CreatedAt'] = Carbon::now();
                    $inputKategori['CreatedBy'] = auth()->user()->NIP;
                    LayananKategori::create($inputKategori);
                }
            }
            inputFiles($request, $inputLayanan['Id']);
            inputFiles($request, $inputLayanan['Id'], 'FileNotaDinas', 1);
            logActivity('default', 'Layanan')->log("Create Pembuatan Layanan " . $inputLayanan['Id']);
            $subject = "Layanan {$layanan->NoTicket} {$layanan->NoTicketRandom} :: Open";
            $to = User::where('NIP', $request->Nip)->first();
            $data = [
                'user' => $to,
                'layanan' => $layanan,
                'url' => route('layanan.eskalasi', $inputLayanan['Id']),
                'message' => 'Telah kami terima dan akan segera kami proses. Silahkan klik tombol dibawah ini untuk melihat detil layanan dan memantau proses penyelesaian layanan'
            ];
            $view = view('layanan.notifications.wa', compact('data'))->render();
            $layananService->sendMail($subject, 'layanan.notifications.mail', $data, $view, 'Melati ', $to);
            $input['CreatedAt'] = Carbon::now();
            $input['Nip'] = $request->Nip;
            $input['Jenis'] = 'Open';
            $input['LayananId'] = $inputLayanan['Id'];
            LayananNotifikasi::create($input);
            DB::commit();
            $msg = 'Pembuatan Layanan Berhasil';
            $type = "success";
        } catch (\Exception $e) {
            DB::rollback();
            $type = "warning";
            $msg = $e->getMessage();
            \Log::error($e->getTraceAsString());
        }

        return redirect(url('layanan'))->with('flash_message', $msg)
            ->with('flash_type', $type);
    }
    public function eskalasi(Layanan $layanan, LayananService $layananService)
    {
        // $this->authorize('layanan.eskalasi');
        $cek  = $layananService->getAllLayanan(['updatedByMe' => TRUE])->where('Layanan.Id', $layanan->Id)->count('Layanan.Id');
        if (!$cek) {
            return redirect(url('layanan'))->with('flash_message', 'Anda Tidak punya akses')
                ->with('flash_type', 'warning');
        }
        logActivity('default', 'Layanan')->log("View Eskalasi Layanan " . $layanan->Id);
        $layananSolver = LayananSolver::where('LayananId', $layanan->Id)->whereNull('DeletedAt');
        $layananGroupSolver = LayananGroupSolver::where('LayananId', $layanan->Id)->whereNull('DeletedAt');
        if ($layananGroupSolver->count('Id') > 0 && $layananSolver->count('Id') == 0) {
            $nipSolver = Solver::whereIn('MstGroupSolverId', $layananGroupSolver->pluck('MstGroupSolverId')->toArray())->pluck('Nip')->toArray('Nip');
        } else {
            $nipSolver = $layananSolver->pluck('Nip')->toArray();
        }
        if ($layanan->StatusLayanan == 4 && $layanan->CreatedBy == auth()->user()->NIP && pegawaiBiasa()) {
            $refStatusLayanan = RefStatusLayanan::where('KdUnitOrgOwnerLayanan', $layanan->KdUnitOrgOwnerLayanan)->whereIn('Id', [3, 5])->orderBy('No')->get();
        } else if (pegawaiBiasa()) {
            $status = [3];
            if ($layanan->NoTicket)
                $status = [3, 4];
            if ($layanan->kdUnitOrgOwnerLayanan != '100205000000')
                $status = [7];

            $refStatusLayanan = RefStatusLayanan::where('KdUnitOrgOwnerLayanan', $layanan->KdUnitOrgOwnerLayanan)->whereIn('Id', $status)->orderBy('No')->get();
        } elseif (auth()->user()->hasRole(['Solver']) && !auth()->user()->hasRole(['Operator', 'Pejabat Struktural', 'Admin Probis Layanan', 'Admin Proses Bisnis', 'Admin Probis Layanan'])) {
            $refStatusLayanan = RefStatusLayanan::where('KdUnitOrgOwnerLayanan', $layanan->KdUnitOrgOwnerLayanan)->whereIn('Id', [3, 6])->orderBy('No')->get();
        } elseif ($layanan->KdUnitOrgOwnerLayanan != '100205000000') {
            $arr = [];
            if (auth()->user()->hasAllRoles(['Solver', 'Pejabat Struktural'])) {
                $arr = [3, 7, 4];
            } elseif (auth()->user()->hasAllRoles(['Solver', 'Operator'])) {
                $arr = [2, 3, 6, 5];
            } elseif (auth()->user()->hasAllRoles(['Solver'])) {
                $arr = [3, 6];
            }
            $refStatusLayanan = RefStatusLayanan::where('KdUnitOrgOwnerLayanan', $layanan->KdUnitOrgOwnerLayanan)->whereIn('Id', $arr)->orderBy('No')->get();
        } else {
            $refStatusLayanan = RefStatusLayanan::where('KdUnitOrgOwnerLayanan', $layanan->KdUnitOrgOwnerLayanan)->orderBy('No')->get();
            if (!$layanan->NoTicket)
                $refStatusLayanan = RefStatusLayanan::where('KdUnitOrgOwnerLayanan', $layanan->KdUnitOrgOwnerLayanan)->whereNotIn('Id', [4, 5])->orderBy('No')->get();
        }
        $refJenis = RefJenisLayanan::orderBy('No');
        if ($layanan->KdUnitOrgOwnerLayanan != '100205000000') {
            $refJenis = $refJenis->where('Id', 'R');
        }
        $data = (object) [
            'method' => 'PATCH',
            'action' => "/layanan/$layanan->Id",
            'title' => request()->edit ? 'Edit Layanan' : 'Detail Layanan',
            'layanan' => $layanan,
            'eskalasi' =>  1,
            'refPrioritas' => RefPrioritas::where('KdUnitOrgOwnerLayanan', $layanan->KdUnitOrgOwnerLayanan)->orderBy('No')->get(),
            'refStatusLayanan' => $refStatusLayanan,
            'refJenisLayanan' => $refJenis->get(),
            'kategori' => Kategori::whereNull('DeletedAt')->get(),
            'nipSolver' => $nipSolver,
            'showForm' => !$layanan || (!$layanan->NoTicket && !$layanan->DeletedAt && !request()->merge) || request()->edit,
            'layananBaru' => Layanan::find(request()->layananBaru),
            'jenisAset' => JnsAset::whereNull('DeletedAt')->get(),
            'aset' => [],
            'isShow' => $layanan->KdUnitOrgOwnerLayanan == '100205000000' ? '1' : '0',
            'isOperator' => $layanan->KdUnitOrgOwnerLayanan != '100205000000' && auth()->user()->hasRole(['Operator']) ? '1' : '0',
            'isSolver' => $layanan->KdUnitOrgOwnerLayanan != '100205000000' && auth()->user()->hasRole(['Solver']) ? '1' : '0',
        ];
        return view('layanan.layanan.form-tiket', compact('data'));
    }
    public function edit(Layanan $layanan, $show = false, $eskalasi = false)
    {

        $this->authorize('layanan.read');
        $cek  = $this->service->getAllLayanan(['updatedByMe' => TRUE])->where('Layanan.Id', $layanan->Id)->count('Layanan.Id');
        if (!$cek) {
            return redirect(url('layanan'))->with('flash_message', 'Anda Tidak punya akses')
                ->with('flash_type', 'warning');
        }
        $data = (object) [
            'method' => 'PATCH',
            'action' => "/layanan/$layanan->Id",
            'title' => $eskalasi ? 'DETAIL LAYANAN' : 'EDIT LAYANAN',
            'layanan' => $layanan,
            'eskalasi' => $eskalasi ? 1 : 0,
            'kembali' => route('layanan.index'),
            'kategori' => Kategori::whereNull('DeletedAt')->get(),
        ];
        return view('layanan.layanan.form', compact('data'));
    }
    public function update(Request $request, Layanan $layanan)
    {
        $this->authorize('layanan.update');
        try {
            DB::beginTransaction();
            $inputLayanan = $this->inputLayanan($request, true);
            $inputLayanan['UpdatedAt'] = Carbon::now();
            $inputLayanan['UpdatedBy'] = auth()->user()->NIP;
            if ($request->pending && $layanan->NoTicket) {

                $inputLayanan['ServiceCatalogId'] = $request->ServiceCatalogId;
                $inputLayanan['ServiceCatalogNama'] = $request->ServiceCatalogNama;
                $inputLayanan['ServiceCatalogKode'] = $request->ServiceCatalogKode;
                $inputLayanan['ServiceCatalogDetailId'] = $request->ServiceCatalogDetailId;
                Layanan::where('Id', $layanan->Id)->update($inputLayanan);
            } else {

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
                if ($request->eskalasi && request()->user()->can('layanan.eskalasi')) {
                    $inputLayanan['ServiceCatalogId'] = $request->ServiceCatalogId;
                    $inputLayanan['ServiceCatalogNama'] = $request->ServiceCatalogNama;
                    $inputLayanan['ServiceCatalogKode'] = $request->ServiceCatalogKode;
                    $inputLayanan['ServiceCatalogDetailId'] = $request->ServiceCatalogDetailId;
                    $inputLayanan['NotaDinas'] = $request->NotaDinas;
                    $inputLayanan['PrioritasLayanan'] = $request->Prioritas;
                    $inputLayanan['NotifikasiEmail'] = $request->NotifikasiEmail;
                    $inputLayanan['KeteranganLayanan'] = $request->KeteranganLayanan;
                    $layanan->NipOperatorOpen == null ? $inputLayanan['NipOperatorOpen'] = auth()->user()->NIP : '';
                    if (!$layanan->JenisLayanan) {
                        $inputLayanan['JenisLayanan'] = $request->JenisLayanan;
                        $inputLayanan['TglLayanan'] = date('Y-m-d');
                        $inputLayanan['NoTicketTahun'] = date('Y');
                        $inputLayanan['NoTicketBulan'] = date('m');
                        $inputLayanan['NoTicketAntrian'] = $this->getNoTiketAntrian($request->JenisLayanan);
                        $inputLayanan['NoTicket'] = $request->JenisLayanan . '-' . $inputLayanan['NoTicketTahun'] . $inputLayanan['NoTicketBulan'] . str_pad($inputLayanan['NoTicketAntrian'], 4, "0", STR_PAD_LEFT);
                        $inputLayanan['TglTicket'] = Carbon::now();
                        $inputLayanan['StatusLayanan'] = 2;
                    }
                    inputFiles($request, $layanan->Id, 'FileNotaDinas', 1);
                    logActivity('default', 'Layanan')->log("Update Eskalasi Layanan " . $layanan->Id);
                } else {
                    logActivity('default', 'Layanan')->log("Update Permintaan Layanan " . $layanan->Id);
                }
                Layanan::where('Id', $layanan->Id)->update($inputLayanan);
                inputFiles($request, $layanan->Id);
            }
            DB::commit();
            $msg = 'Edit Layanan  Berhasil';
            $type = "success";
        } catch (\Throwable $e) {
            DB::rollback();
            $type = "warning";
            $msg = $e->getMessage();
            \Log::error($e->getTraceAsString());
        }
        $url = route('layanan.eskalasi', $layanan->Id);
        if ($request->eskalasi == 0)
            $url = url('layanan');
        if ($request->pending)
            $url = route('layanan.index') . '?pending=1';
        if ($request->kembali)
            $url = route($request->kembali);
        return redirect($url)->with('flash_message', $msg)
            ->with('flash_type', $type);
    }
    public function destroy(Layanan $layanan)
    {
        $this->authorize('layanan.delete');
        $response = self::$response;
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
    public function destroyKategori(LayananKategori $kategori)
    {
        $this->authorize('layanan.delete');
        $response = self::$response;
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
    public function merge($parentId, $id)
    {
        $inputLayanan['ParentId'] = $parentId;
        $inputLayanan['DeletedAt'] = Carbon::now();
        $inputLayanan['DeletedBy'] = auth()->user()->NIP;
        Layanan::where('Id', $id)->update($inputLayanan);
        $layanan = Layanan::find($id);
        $inputTL['Id'] = $id;
        $inputTL['LayananId'] = $parentId;
        $inputTL['Keterangan'] = $layanan->PermintaanLayanan;
        $inputTL['Nip'] = $layanan->Nip;
        $inputTL['CreatedAt'] = Carbon::now();
        $inputTL['CreatedBy'] = $layanan->Nip;
        LayananTL::create($inputTL);
        logActivity('default', 'Layanan')->log("Merge Layanan parentId $parentId Id $id");
        return redirect(route('layanan.eskalasi', $parentId))->with('flash_message', 'Berhasil Gabungkan Tiket ')
            ->with('flash_type', 'success');
    }

    public function export(Request $request, LayananService $layananService)
    {
        $data = $layananService->getAllLayanan()->with(['operatorOpen', 'serviceCatalog'])->without('pelapor', 'layananKategori');
        if ($request->tglStart) {
            $data->whereBetween('Layanan.TglLayanan', [$request->tglStart . ' 00:00:00', $request->tglEnd . ' 23:59:59']);
        }
        if ($request->statusLayanan)
            $data->whereIn('StatusLayanan', $request->statusLayanan);
        if ($request->serviceCatalog) {
            if ($request->serviceCatalog[0] == "undefined") {
                $data->whereNull('ServiceCatalogKode');
            } else {
                $data->whereIn('ServiceCatalogKode', $request->serviceCatalog);
            }
        }
        if ($request->prioritasLayanan)
            $data->where('PrioritasLayanan', $request->prioritasLayanan);
        if (request()->groupSolver) {
            $groupSolver = request()->groupSolver;
            $solver = request()->solver;
            if (request()->groupSolver[0] == 'Kosong') {
                $remove = array_shift($groupSolver);
                $data->where(function ($data) use ($groupSolver) {
                    $data->doesntHave('groupSolver')->orwhereHas('groupSolver', function ($q) use ($groupSolver) {
                        $q->whereIn('MstGroupSolverId', $groupSolver);
                    });;
                });
            } else {
                $data->whereHas('groupSolver', function ($q) use ($groupSolver) {
                    $q->whereIn('MstGroupSolverId', $groupSolver);
                });
            }
        }
        if ($request->solver) {
            $solver = $request->solver;
            $data->whereHas('solver', function ($q) use ($solver) {
                $q->whereIn('Nip', $solver);
            });
        }
        if ($request->tematik) {
            $tematik = $request->tematik;
            $data->whereHas('serviceCatalog.tematik', function ($q) use ($tematik) {
                $q->whereIn('MstTematikId', $tematik);
            });
        }
        if ($request->sla) {
            $data->selectRaw("Layanan.*,NormaWaktu,Limit,Datediff(hour, [tglticket], CASE WHEN [layanan].statuslayanan IN ( 5, 6, 7 ) THEN isnull([Layanan].TglSolved,[Layanan].[TglTicket]) ELSE Getdate() END) LamaJamLayanan");
            $data->leftJoin('ServiceCatalogDetail', 'Layanan.ServiceCatalogDetailId', '=', 'ServiceCatalogDetail.Id');
            $data->whereNotIn('StatusLayanan', [5, 6, 7])->whereNotNull('NoTicket')->whereNotNull('ServiceCatalogDetailId');
            if (count($request->sla) == 1) {
                if ($request->sla[0] == 'melewati')
                    $data->whereRaw("isnull(Limit,0)  < Datediff(hour, [tglticket], CASE WHEN [layanan].statuslayanan IN ( 5, 6, 7 ) THEN isnull([Layanan].TglSolved,[Layanan].[TglTicket]) ELSE Getdate() END)");
                if ($request->sla[0] == 'tidak_melewati')
                    $data->whereRaw("isnull(Limit,0)  >= Datediff(hour, [tglticket], CASE WHEN [layanan].statuslayanan IN ( 5, 6, 7 ) THEN isnull([Layanan].TglSolved,[Layanan].[TglTicket]) ELSE Getdate() END)");
                if ($request->sla[0] == 'mendekati_deadline')
                    $data->whereIn('StatusLayanan', [2, 3])->whereIn('Layanan.Id', function ($query) {
                        $query->select('Id')
                            ->from('vRptSLADeadline');
                    });
            }
        }
        $data = $data->orderBy('UpdatedAt', 'desc');
        $data = $data->get();
        return view('layanan.layanan.layananExport', compact('data'));
        // return Excel::download(new LayananExport($data->get()), 'layanan'. Carbon::now() . '.xlsx');
    }
}
