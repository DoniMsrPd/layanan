<?php

namespace App\Http\Controllers\System;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Yajra\Datatables\Datatables;
use Spatie\Permission\Models\Role;
use Modules\Pegawai\Models\UnitOrg;
use Illuminate\Support\Facades\Session;
use App\Models\System\Pegawai;
use Illuminate\Support\Facades\Validator;

class PegawaiController extends Controller
{
    private static $response = [
        'success' => false,
        'data'    => null,
        'message' => null
    ];
    public function index(Request $request)
    {
        // $this->authorize('user.read');
        logActivity('default', 'Pegawai')->log("View Pegawai");
        $role = Role::all()->pluck('name', 'id');
        return view('system.pegawai.index', compact('role'));
    }
    public function dataTables(Request $request)
    {

        $data = Pegawai::where('stsPensiun', '0'); //->where('KdUnitOrg', 'like', rtrim(kdUnitOrgOwner(), '0') . '%');
        $struktural = $request->struktural;
        return Datatables::of($data)->order(function ($query) use ($struktural) {
            if ($struktural) {
                $query->orderByRaw("JnsJabatan, KdUnitOrg  ASC");
            } else {
                $query->orderByRaw("KdUnitOrg , CASE WHEN JnsJabatanCur='' THEN 9 ELSE JnsJabatanCur END asc");
            }
        })
            ->addIndexColumn()
            ->addColumn('pilih', function ($data) {
                return '<button
                        class="mb-2 mr-2 btn btn-primary pilih-pegawai"
                        data-nip="' . $data->Nip . '"
                        data-nip_baru="' . $data->NipBaru . '"
                        data-nm_pangkat="' . $data->NmPangkat . '"
                        data-nm_golongan="' . $data->NmGolongan . '"
                        data-nm_jabatan="' . $data->NmJabatan . '"
                        data-kd_jabatan="' . $data->KdJabatan . '"
                        data-nm_unit_org="' . $data->NmUnitOrg . '"
                        data-nm_unit_org_induk="' . $data->NmUnitOrgInduk . '"
                        data-kd_unit_org="' . $data->KdUnitOrg . '"
                        data-nm_kantor_adm="' . $data->NmKantorAdm . '"
                        data-no_hp="' . $data->NoHp . '"
                        data-email="' . $data->Email . '"
                        data-nama="' . $data->NmPeg . '">
                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-share"><path d="M4 12v8a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2v-8"></path><polyline points="16 6 12 2 8 6"></polyline><line x1="12" y1="2" x2="12" y2="15"></line></svg>
                    </button>';
            })
            ->addColumn('pilih2', function ($data) {
                return '<button
                        class="mb-2 mr-2 btn btn-primary pilih-pegawai2"
                        data-nip="' . $data->Nip . '"
                        data-nip_baru="' . $data->NipBaru . '"
                        data-nm_pangkat="' . $data->NmPangkat . '"
                        data-nm_golongan="' . $data->NmGolongan . '"
                        data-nm_jabatan="' . $data->NmJabatan . '"
                        data-nm_unit_org="' . $data->NmUnitOrg . '"
                        data-nm_unit_org_induk="' . $data->NmUnitOrgInduk . '"
                        data-kd_unit_org="' . $data->KdUnitOrg . '"
                        data-nm_kantor_adm="' . $data->NmKantorAdm . '"
                        data-nama="' . $data->NmPeg . '">
                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-share"><path d="M4 12v8a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2v-8"></path><polyline points="16 6 12 2 8 6"></polyline><line x1="12" y1="2" x2="12" y2="15"></line></svg>
                    </button>';
            })
            ->addColumn('pilih3', function ($data) {
                return '<button
                        class="mb-2 mr-2 btn btn-primary pilih-pegawai3"
                        data-nip="' . $data->Nip . '"
                        data-nip_baru="' . $data->NipBaru . '"
                        data-nm_pangkat="' . $data->NmPangkat . '"
                        data-nm_golongan="' . $data->NmGolongan . '"
                        data-nm_jabatan="' . $data->NmJabatan . '"
                        data-nm_unit_org="' . $data->NmUnitOrg . '"
                        data-nm_unit_org_induk="' . $data->NmUnitOrgInduk . '"
                        data-kd_unit_org="' . $data->KdUnitOrg . '"
                        data-nm_kantor_adm="' . $data->NmKantorAdm . '"
                        data-nama="' . $data->NmPeg . '">
                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-share"><path d="M4 12v8a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2v-8"></path><polyline points="16 6 12 2 8 6"></polyline><line x1="12" y1="2" x2="12" y2="15"></line></svg>
                    </button>';
            })->addColumn('pilih4', function ($data) {
                return '<button
                        class="mb-2 mr-2 btn btn-primary pilih-pegawai4"
                        data-nip="' . $data->Nip . '"
                        data-nip_baru="' . $data->NipBaru . '"
                        data-nm_pangkat="' . $data->NmPangkat . '"
                        data-nm_golongan="' . $data->NmGolongan . '"
                        data-nm_jabatan="' . $data->NmJabatan . '"
                        data-nm_unit_org="' . $data->NmUnitOrg . '"
                        data-nm_unit_org_induk="' . $data->NmUnitOrgInduk . '"
                        data-kd_unit_org="' . $data->KdUnitOrg . '"
                        data-nm_kantor_adm="' . $data->NmKantorAdm . '"
                        data-nama="' . $data->NmPeg . '">
                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-share"><path d="M4 12v8a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2v-8"></path><polyline points="16 6 12 2 8 6"></polyline><line x1="12" y1="2" x2="12" y2="15"></line></svg>
                    </button>';
            })->addColumn('pilih5', function ($data) {
                return '<button
                        class="mb-2 mr-2 btn btn-primary pilih-pegawai5"
                        data-nip="' . $data->Nip . '"
                        data-nip_baru="' . $data->NipBaru . '"
                        data-nm_pangkat="' . $data->NmPangkat . '"
                        data-nm_golongan="' . $data->NmGolongan . '"
                        data-nm_jabatan="' . $data->NmJabatan . '"
                        data-nm_unit_org="' . $data->NmUnitOrg . '"
                        data-nm_unit_org_induk="' . $data->NmUnitOrgInduk . '"
                        data-kd_unit_org="' . $data->KdUnitOrg . '"
                        data-nm_kantor_adm="' . $data->NmKantorAdm . '"
                        data-nama="' . $data->NmPeg . '">
                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-share"><path d="M4 12v8a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2v-8"></path><polyline points="16 6 12 2 8 6"></polyline><line x1="12" y1="2" x2="12" y2="15"></line></svg>
                    </button>';
            })->addColumn('pilih6', function ($data) {
                return '<button
                        class="mb-2 mr-2 btn btn-primary pilih-pegawai6"
                        data-nip="' . $data->Nip . '"
                        data-nip_baru="' . $data->NipBaru . '"
                        data-nm_pangkat="' . $data->NmPangkat . '"
                        data-nm_golongan="' . $data->NmGolongan . '"
                        data-nm_jabatan="' . $data->NmJabatan . '"
                        data-nm_unit_org="' . $data->NmUnitOrg . '"
                        data-nm_unit_org_induk="' . $data->NmUnitOrgInduk . '"
                        data-kd_unit_org="' . $data->KdUnitOrg . '"
                        data-nm_kantor_adm="' . $data->NmKantorAdm . '"
                        data-nama="' . $data->NmPeg . '">
                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-share"><path d="M4 12v8a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2v-8"></path><polyline points="16 6 12 2 8 6"></polyline><line x1="12" y1="2" x2="12" y2="15"></line></svg>
                    </button>';
            })->addColumn('pilih7', function ($data) {
                return '<button
                        class="mb-2 mr-2 btn btn-primary pilih-pegawai7"
                        data-nip="' . $data->Nip . '"
                        data-nip_baru="' . $data->NipBaru . '"
                        data-nm_pangkat="' . $data->NmPangkat . '"
                        data-nm_golongan="' . $data->NmGolongan . '"
                        data-nm_jabatan="' . $data->NmJabatan . '"
                        data-nm_unit_org="' . $data->NmUnitOrg . '"
                        data-nm_unit_org_induk="' . $data->NmUnitOrgInduk . '"
                        data-kd_unit_org="' . $data->KdUnitOrg . '"
                        data-nm_kantor_adm="' . $data->NmKantorAdm . '"
                        data-nama="' . $data->NmPeg . '">
                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-share"><path d="M4 12v8a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2v-8"></path><polyline points="16 6 12 2 8 6"></polyline><line x1="12" y1="2" x2="12" y2="15"></line></svg>
                    </button>';
            })->addColumn('pilih8', function ($data) {
                return '<button
                        class="mb-2 mr-2 btn btn-primary pilih-pegawai8"
                        data-nip="' . $data->Nip . '"
                        data-nip_baru="' . $data->NipBaru . '"
                        data-nm_pangkat="' . $data->NmPangkat . '"
                        data-nm_golongan="' . $data->NmGolongan . '"
                        data-nm_jabatan="' . $data->NmJabatan . '"
                        data-nm_unit_org="' . $data->NmUnitOrg . '"
                        data-nm_unit_org_induk="' . $data->NmUnitOrgInduk . '"
                        data-kd_unit_org="' . $data->KdUnitOrg . '"
                        data-nm_kantor_adm="' . $data->NmKantorAdm . '"
                        data-nama="' . $data->NmPeg . '">
                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-share"><path d="M4 12v8a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2v-8"></path><polyline points="16 6 12 2 8 6"></polyline><line x1="12" y1="2" x2="12" y2="15"></line></svg>
                    </button>';
            })->addColumn('pilih9', function ($data) {
                return '<button
                        class="mb-2 mr-2 btn btn-primary pilih-pegawai9"
                        data-nip="' . $data->Nip . '"
                        data-nip_baru="' . $data->NipBaru . '"
                        data-nm_pangkat="' . $data->NmPangkat . '"
                        data-nm_golongan="' . $data->NmGolongan . '"
                        data-nm_jabatan="' . $data->NmJabatan . '"
                        data-nm_unit_org="' . $data->NmUnitOrg . '"
                        data-nm_unit_org_induk="' . $data->NmUnitOrgInduk . '"
                        data-kd_unit_org="' . $data->KdUnitOrg . '"
                        data-nm_kantor_adm="' . $data->NmKantorAdm . '"
                        data-nama="' . $data->NmPeg . '">
                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-share"><path d="M4 12v8a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2v-8"></path><polyline points="16 6 12 2 8 6"></polyline><line x1="12" y1="2" x2="12" y2="15"></line></svg>
                    </button>';
            })->addColumn('NIPNM_PEG', function ($data) {
                $nimnm_peg = $data->NmPeg . '<br>' . '<span style="color: grey; font-size: 13.5px;">' . $data->Nip . '</span>';
                return $nimnm_peg;
            })->addColumn('NIPPEG', function ($data) {
                $NIPPEG = $data->Nip ?? $data->NipPejabat;
                return $NIPPEG;
            })->rawColumns([
                'pilih', 'pilih2', 'pilih3', 'pilih4', 'pilih5', 'pilih6', 'pilih7', 'pilih8', 'pilih9', 'NIPNM_PEG', 'NIPPEG'
            ])->make(true);
    }
    public function create()
    {
        $data = (object) [
            'method' => 'POST',
            'action' => '/core/pegawai',
            'title' => 'Tambah Pegawai',
            'user' => []
        ];
        return view('system.pegawai.form', compact('data'));
    }
    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => ['required', 'string', 'max:255'],
            'NIP' => ['required', 'string', 'max:255', 'unique:users'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);
        User::create([
            'name' => $request->name,
            'username' => $request->name,
            'email' => $request->email,
            'NIP' => $request->NIP,
            'password' => Hash::make($request->password),
        ]);
        return redirect(url('core/pegawai'))->with('flash_message', 'Berhasil Tambah User')->with('flash_type', 'success');
    }
    public function edit(User $pegawai)
    {
        $data = (object) [
            'method' => 'PATCH',
            'action' => '/core/pegawai/' . $pegawai->id,
            'title' => 'Edit Pegawai',
            'user' => $pegawai
        ];
        return view('system.pegawai.form', compact('data'));
    }
    public function update(Request $request, User $pegawai)
    {
        $this->validate($request, [
            'name' => ['required', 'string', 'max:255',  Rule::unique('users')->ignore($pegawai->id)],
            'NIP' => ['required', 'string', 'max:255', Rule::unique('users')->ignore($pegawai->id)],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($pegawai->id)],
            // 'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);
        $pegawai->name = $request->name;
        $pegawai->username = $request->name;
        $pegawai->email = $request->email;
        $pegawai->NIP = $request->NIP;
        $pegawai->save();
        return redirect(url('core/pegawai'))->with('flash_message', 'Berhasil Tambah User')->with('flash_type', 'success');
    }

    public function destroy(User $pegawai)
    {
        $response = self::$response;
        $delete = false;

        try {
            $delete = $pegawai->delete();
            $response['success'] = $delete;
            $response['message'] = $delete ? 'Berhasil Hapus Data' : 'Gagal Hapus Data';
            logActivity('default', ' Aset TI')->log("Delete Pegawai ");

            return response()->json($response);
        } catch (\Exception $e) {
            $response['success'] = false;
            $response['message'] = $e->getMessage();
            return response()->json($response);
        }
    }
    public function autoComplete(Request $request)
    {
        $query = $request->get('term', '');
        $pegawai = Pegawai::where('sts_pensiun', 0)->where('nm_peg', 'LIKE', '%' . $query . '%')->orWhere('nip', 'LIKE', '%' . $query . '%')->limit(10)->get();
        // dd($pegawai);
        $data = array();
        foreach ($pegawai as $peg) {
            $data[] = array('value' => $peg->NIP . ' / ' . $peg->NM_PEG, 'id' => $peg->NIP);
        }
        if (count($data)) {
            return $data;
        } else {
            return ['value' => 'No Result Found', 'id' => ''];
        }
    }
    public function autoComplete2(Request $request)
    {
        $query = $request->get('term', '');
        DB::enableQueryLog();
        $pegawai = UnitOrg::selectRaw('NIP_PEJABAT NIP,NM_PEG')->where('kd_unit_org', 'like', substr(auth()->user()->pegawai->KdUnitOrg, 0, 6)  . '000000%')
            ->where(function ($q) use ($query) {
                $q->where('nm_peg', 'LIKE', '%' . $query . '%')->orWhere('nip_pejabat', 'LIKE', '%' . $query . '%');
            });
        $pegawai = UserImpersonate::selectRaw('SISDM_DATA_CURRENT.NIP,SISDM_DATA_CURRENT.NM_PEG')->unionAll($pegawai)
            ->where('KdUnitOrgImpersonate', 'like', substr(auth()->user()->pegawai->KdUnitOrg, 0, 6) . '%')
            ->whereIn('RefJenisImpersonateId', [2, 3])
            ->where(function ($q) use ($query) {
                $q->where('SISDM_DATA_CURRENT.nm_peg', 'LIKE', '%' . $query . '%')->orWhere('UserImpersonate.Nip', 'LIKE', '%' . $query . '%');
            })
            ->leftJoin('SISDM_DATA_CURRENT', 'SISDM_DATA_CURRENT.NIP', '=', 'UserImpersonate.Nip')->limit(10)->get();
        // dd(DB::getQueryLog());
        // dd($pegawai);
        $data = array();
        foreach ($pegawai as $peg) {
            $data[] = array('value' => $peg->NIP . ' / ' . $peg->NM_PEG, 'id' => $peg->NIP);
        }
        if (count($data)) {
            return $data;
        } else {
            return ['value' => 'No Result Found', 'id' => ''];
        }
    }
    public function assignRole($id)
    {
        $user = User::find($id);
        $nip = $user->NIP;
        $role = null;
        if (request()->user()->can('user.read-scope')) {
            $role = Role::whereIn('name', ['Operator', 'Pejabat Struktural'])->get();
        }
        if (request()->user()->can('user.read')) {
            $role = Role::get();
        }
        if (!isset($role)) {
            $this->authorize('user.read');
        }
        $groupedRoles = $role->split(ceil($role->count() / 3));
        return view('system.pegawai.assignrole', compact('role', 'groupedRoles', 'user'));
    }
}
