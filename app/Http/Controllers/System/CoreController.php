<?php

namespace App\Http\Controllers\System;

use App\Models\System\KonselingFile;
use App\Models\User;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Response;
use Modules\Core\Entities\SintagFile;
use Illuminate\Support\Facades\Storage;
use Modules\Core\Entities\SiJaliFile;
use Yajra\DataTables\DataTables;

class CoreController extends Controller
{

    public function index()
    {
        return view('core::index');
    }

    public function userDatatables($role = '')
    {
        if ($role) {
            $data = User::with('roles')->whereHas('roles', function ($q) use ($role) {
                $q->where('id', $role);
            });
        } else {
            $data = User::with('roles')
                ->select('users.*', 'spg.KdUnitOrg', 'spg.JnsJabatanCur')
                ->join('SpgDataCurrent as spg', 'spg.Nip', '=', 'users.NIP')
                ->whereHas('pegawai', function ($q) {
                    if (request()->user()->can('user.read-scope')) {
                        $q->where('KdUnitOrg', 'like', rtrim(kdUnitOrgOwner(), '0') . '%');
                    }
                });
        }
        return DataTables::of($data)->order(function ($query) use ($role) {
            if ($role) {
            } else {
                $query->orderBy('spg.KdUnitOrg', 'asc')
                    ->orderBy('spg.JnsJabatanCur', 'asc');
            }
        })
            ->addIndexColumn()
            ->addColumn('roles', function ($data) {
                $roles = '';
                // $kdunit = kdUnitOrgOwner($data->NIP);
                // $kdunitOwner = kdUnitOrgOwner();
                $role1 = $data->roles;
                // if ($kdunit != $kdunitOwner) {
                //     $role1 = $data->roles->where('id', '7');
                // }
                $first = true;
                foreach ($role1 as $role) {
                    if (!$first) {
                        $roles .= ', <code>' . $role->name . '</code>';
                    } else {
                        $roles .= '<code>' . $role->name . '</code> ';
                        $first = false;
                    }
                }
                return $roles;
            })->addColumn('action', function ($data) {
                $editButton = '';
                $deleteButton = '';
                $editButton = '<a href="' . route('core.pegawai.edit', $data->id) . '" class="mb-2 mr-2 btn btn-warning btn-sm" title="Ubah" title-pos="up">' . btnEdit() . '</a>';
                $deleteButton = '<a style="margin-left:10px" data-id="' . $data->id . ' " data-url="/pegawai/' . $data->id . ' " class="mb-2 mr-2 btn btn-danger btn-sm deleteData" data-title ="Data" title="Hapus" title-pos="up">' . btnDelete() . '</a>';
                return '<span class="btn-group" role="group">' . $editButton . '' . $deleteButton . '</span>';
            })
            ->rawColumns(['roles', 'action'])
            ->make(true);
    }
}
