<?php

namespace App\Http\Controllers\System;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\System\Pegawai;
use Yajra\DataTables\DataTables;


class KepegawaianController extends Controller
{

    public function lookupPegawai(Request $request)
    {
        if ($request->jenis == '1') {
            $query = DB::table('SpgDataCurrent');
            $kdUnitOrg = userKdOrg();
            if (Auth::user()->search_scope == 2) {
                $query->whereRaw("substring(KdUnitOrg,1,4) = substring('$kdUnitOrg', 1, 4)");
            } else if (Auth::user()->search_scope == 3) {
                $query->whereRaw("substring(KdUnitOrg,1,6) = substring('$kdUnitOrg', 1, 6)");
            } else {
                $query->whereRaw("( substring(KdUnitOrg,1,4) = substring('$kdUnitOrg', 1, 4) or substring(KdUnitOrg,1,6) = substring('$kdUnitOrg', 1, 6) )");
            }

            if (isset($request->pensiun)) {
                $query->where('stspensiun', 1);
            } else {
                $query->where('StsPensiun', 0);
            }


            if ($request->from && Auth()->user()->pegawai->KdKantorAdm == '67') {
                $query->where('KdKantorAdm', 67);
            }

            $query = $query->get();

            return DataTables::of($query)

                ->addColumn('pilih', function ($data) {
                    return '<button
                        class="btn btn-icon btn-outline-primary waves-effect picknip"
                        data-balloon="Pilih Pegawai" data-balloon-pos="up"
                        data-nip="' . $data->Nip . '"
                        data-nip_baru="' . $data->NipBaru . '"
                        data-nm_peg="' . $data->NmPeg . '"
                        data-gelar_depan="' . $data->GelarDepan . '"
                        data-gelar_belakang="' . $data->GelarBelakang . '"
                        data-nm_unit_org="' . $data->NmUnitOrg . '"
                        data-nama="' . $data->NmPeg . '">
                            <i data-feather="check-square"></i>
                    </button>';
                })
                ->rawColumns(['pilih'])
                ->make(true);
        } else {
            $query = Pegawai::query();
            if (isset($request->pensiun)) {
                $query->where('stspensiun', 1);
            } else {
                $query->where('StsPensiun', 0);
            }
            // if ($request->scopeKepegawaian && Auth::user()->search_scope != 1) :
            //     $kdUnitOrg = userKdOrg();
            //     if (Auth::user()->search_scope == 2) {
            //         $query->whereRaw("substring(KdUnitOrg,1,4) = substring('$kdUnitOrg', 1, 4)");
            //     } else  if (Auth::user()->search_scope == 3) {
            //         $query->whereRaw("substring(KdUnitOrg,1,6) = substring('$kdUnitOrg', 1, 6)");
            //     } else {
            //         $query->whereRaw("( substring(KdUnitOrg,1,4) = substring('$kdUnitOrg', 1, 4) or substring(KdUnitOrg,1,6) = substring('$kdUnitOrg', 1, 6) )");
            //     }
            // endif;
            $request->jns_jabatan_cur == 1 ? $query->where('JnsJabatanCur', 1) : '';


            if ($request->from && Auth()->user()->pegawai->KdKantorAdm == '67') {
                $query->where('KdKantorAdm', 67);
            }

            $data = $query;

            return DataTables::of($data)
                ->addColumn('pilih_imp', function ($data) {
                    return '<button
                        class="btn btn-icon btn-outline-primary waves-effect pickImp"
                        data-balloon="Pilih Pegawai" data-balloon-pos="up"
                        data-nip="' . $data->Nip . '"
                        data-nip_baru="' . $data->NipBaru . '"
                        data-nm_unit_org="' . $data->NmUnitOrg . '"
                        data-nama="' . $data->NmPeg . '">
                            <i data-feather="check-square"></i>
                    </button>';
                })
                ->addColumn('pilih', function ($data) {
                    return '<button
                        class="btn btn-icon btn-outline-primary waves-effect picknip"
                        data-balloon="Pilih Pegawai" data-balloon-pos="up"
                        data-nip="' . $data->Nip . '"
                        data-nip_baru="' . $data->NipBaru . '"
                        data-nm_peg="' . $data->NmPeg . '"
                        data-gelar_depan="' . $data->GelarDepan . '"
                        data-gelar_belakang="' . $data->GelarBelakang . '"
                        data-nm_unit_org="' . $data->NmUnitOrg . '"
                        data-nama="' . $data->NmPeg . '">
                            <i data-feather="check-square"></i>
                    </button>';
                })
                ->addColumn('pilih_pegawai', function ($data) {
                    return '<button
                            class="btn btn-icon btn-outline-primary waves-effect pick_pegawai"
                            data-balloon="Pilih Pegawai" data-balloon-pos="up"
                            data-nip="' . $data->Nip . '"
                            data-jabatan="' . $data->NmJabatan . '"
                            data-nama="' . $data->NmPeg . '">
                                <i data-feather="check-square"></i>
                        </button>';
                })
                ->addColumn('linkUnitOrg', function ($data) {
                    return "<a href='/v3/graph?q=$data->KdUnitOrg'
                                class='text-primary' data-toggle='tooltip' title='' data-original-title='Posisi Dalam Unit Organisasi'>
                                <svg xmlns='http://www.w3.org/2000/svg' width='14' height='14' viewBox='0 0 24 24' fill='none' stroke='currentColor' stroke-width='2' stroke-linecap='round' stroke-linejoin='round' class='feather feather-external-link ficon'><path d='M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6'></path><polyline points='15 3 21 3 21 9'></polyline><line x1='10' y1='14' x2='21' y2='3'></line></svg>
                             </a>";
                })

                ->rawColumns(['pilih_imp', 'pilih_pegawai', 'linkUnitOrg'])
                // ->toJson();
                ->make(true);
        }
    }
}
