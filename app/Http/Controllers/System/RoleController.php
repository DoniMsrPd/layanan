<?php

namespace App\Http\Controllers\System;

use Illuminate\Http\Request;

use App\Http\Controllers\Controller;
use App\Models\System\Permission;
use App\Models\System\Role;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Traits\HasPermissions;
use Yajra\DataTables\DataTables;

class RoleController extends Controller
{
    use HasPermissions;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $roles = Role::filtered()
            ->with('permissions')
            ->paginate();
        logActivity('default', 'Role')->log("Read Role Menu");

        return view('system.roles.index')->with('roles', $roles);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $this->authorize('role.create');
        $permissions = Permission::orderBy('Name', 'ASC')->get();
        $groupedPermissions = $permissions->groupBy(function ($item, $key) {
            $dots = explode('.', $item->name);
            return $dots[0];
        });
        $menu = [];
        foreach ($permissions as $rk) {
            $itemName = $rk->name;
            $dots = explode('.', $itemName);
            if (count($dots) > 1) {
                !in_array($dots[0], $menu) ? array_push($menu, $dots[0]) : "";
            } else {
                !in_array($itemName, $menu) ? array_push($menu, $itemName) : "";
            }
        }
        return view('system.roles.create', compact('permissions', 'groupedPermissions', 'menu'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|unique:sys_roles',
            'permissions' => 'required',
        ]);

        $name = $request['name'];
        $role = new Role();
        $role->name = $name;

        $permissions = $request['permissions'];

        $role->save();

        foreach ($permissions as $permission) {
            $p = Permission::where('id', '=', $permission)->firstOrFail();
            $role = Role::where('name', '=', $name)->first();
            $role->givePermissionTo($p);
        }

        logActivity('default', 'Role')
            // ->performedOn($role)
            ->log("Role {$role->name} has been created");
        return redirect()->route('core.role.index')
            ->with('flash_message', 'Role ' . $role->name . ' added!')
            ->with('flash_type', 'success');
    }

    public function userRoleStore(Request $request, User $user)
    {
        $user = User::find($request->userid);
        if ($user->roles->isEmpty() == false) {
            foreach ($user->roles as $role) {
                $user->removeRole($role);
            }
        }
        $user->assignRole($request->roles);

        logActivity('default', 'User')->log("Assign Role {$user->NIP}");
        return redirect('/core/pegawai')
            ->with('flash_message', 'User successfully edited.')
            ->with('flash_type', 'success');
    }
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Role $role)
    {
        $users = User::with(['roles', 'pegawai'])
            ->filtered()
            ->whereHas('roles', function ($q) use ($role) {
                $q->where('name', $role->name);
            });

        if (request()->user()->can('user.read-scope')) {
            $users->filterOrg();
        }
        $users->paginate();

        return view(
            'system.roles.show',
            compact('role', 'users')
        );
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Role $role)
    {
        $this->authorize('role.read');
        $permissions = Permission::orderBy('Name', 'ASC')->get();
        $groupedPermissions = $permissions->groupBy(function ($item, $key) {
            $itemName = $item->name;
            $dots = explode('.', $itemName);
            if (count($dots) > 1) {
                return $dots[0] . $dots[1];
            } else {
                return $itemName;
            }
        });
        $menu = [];
        foreach ($permissions as $rk) {
            $itemName = $rk->name;
            $dots = explode('.', $itemName);
            if (count($dots) > 1) {
                !in_array($dots[0], $menu) ? array_push($menu, $dots[0]) : "";
            } else {
                !in_array($itemName, $menu) ? array_push($menu, $itemName) : "";
            }
        }
        return view(
            'system.roles.edit',
            compact('role', 'permissions', 'groupedPermissions', 'menu')
        );
    }

    public function editHasPermissions(Request $request, Role $role)
    {
        $roleHasPermissionId = $role->permissions->pluck('id');
        $type = $request->type;

        if ($type == 'has') :
            $permissions = Permission::filtered()->whereIn('Id', $roleHasPermissionId);
        else :
            $permissions = Permission::filtered()->whereNotIn('Id', $roleHasPermissionId);
        endif;

        $groupedPermissions = $permissions->get()->groupBy(function ($item, $key) {
            $dots = explode('.', $item->name);
            if (isset($dots[1]))
                return $dots[0] . $dots[1];
            else
                return $dots[0];
        });

        return view(
            'system.roles._list-permission',
            compact('role', 'groupedPermissions', 'type')
        );
    }

    public function updateHasPermissions(Request $request, Role $role)
    {
        $type = $request->type;
        $permission = Permission::where('id', '=', $request->value)->firstOrFail(); //Get corresponding form permission in db

        if ($type == 'has') :
            $role->revokePermissionTo($permission);
            $msg = 'revoke';
        else :
            $role->givePermissionTo($permission);
            $msg = 'give';
        endif;

        return response()->json($msg, 200);
    }

    public function update(Request $request)
    {
        $this->authorize('role.update');
        $role = Role::where('Id', $request->id)->first();
        $role->name = $request->name;
        $role->save();
        $permissions = Permission::all();

        logActivity('default', 'Role')->log("Update Role {$request->name}");
        $role->syncPermissions($request->permissions);
        $this->forgetCachedPermissions();

        return redirect()->route('core.role.index')
            ->with('flash_message', 'Role ' . $role->name . ' telah diperbarui')
            ->with('flash_type', 'success');
    }
    public function hapusdetail(Request $request)
    {
        //  DB::beginTransaction();
        try {
            $user = User::where('NIP', $request->nip)->firstorFail();
            $user->removeRole($request->role);
            // $dd = RoleUser::where('role_id', $request->role_id)->where('model_id', $request->model_id)->delete();
            // DB::commit();
            logActivity('default')->log('Hapus user role');

            $pesan = 'Berhasil hapus data';
            $type = "success";
        } catch (\Exception $e) {
            // DB::rollback();
            $pesan = $e->getMessage();
            $type = "warning";
        }
        return response()->json(['type' => 'success', 'text' => $pesan]);
    }
    public function dataTablesPegawai()
    {
        if (!request()->user()->canAny(['role.read', 'role.read-scope'])) {
            return;
        }
        $data = User::with('roles', 'pegawai')
            ->whereHas('roles', function ($q) {
                $q->where('name', request()->role);
            });
        if (request()->user()->can('role.read-scope')) {
            $data = $data->filterOrg();
        }
        return DataTables::of($data)
            ->addColumn('action', function ($data) {
                return '<span class="btn-group" role="group" aria-label="Basic example">' .
                    '<a title="Hapus Role" id="deleteRole" data-id="' . $data->id . '" data-nama="' . $data->name . '"  data-nip="' . $data->NIP . '" class="mb-2 mr-2 btn btn-danger" title="Hapus" title-pos="up">' . btnDelete() . '</a></span>';
            })
            ->rawColumns(['action', 'permissions'])->make(true);
    }

    public function tambahuser(Request $request)
    {
        //  DB::beginTransaction();
        try {
            $user = User::where('NIP', $request->nip)->first();
            if ($user <> null) {
                $user->assignRole($request->role);
                logActivity('default')->log("Tambah role $request->role user nip $request->nip");
                $pesan = 'Berhasil tambah pegawai';
                $type = "success";
            } else {
                logActivity('default')->log("Tambah role $request->role user nip $request->nip");
                $pesan = 'gagal tambah pegawai';
                $type = "warning";
            }
        } catch (\Exception $e) {
            // DB::rollback();
            $pesan = $e->getMessage();
            $type = "warning";
        }
        return response()->json(['type' => $type, 'text' => $pesan]);
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Role $role)
    {
        $nama = $role->name;
        $role->delete();

        return redirect()->route('role.index')
            ->with('flash_message', "Role {$nama} telah dihapus!")
            ->with('flash_type', 'warning');
    }

    public function destroyUserHasRole(Role $role, User $user)
    {
        $user->removeRole($role);
        return response()->json('oke', 200);
    }

    public function autoComplete(Request $request)
    {
        $query = $request->get('term', '');
        $roles = Role::select('name')
            ->where('name', 'LIKE', '%' . $query . '%')
            ->limit(10)->get();
        $data = array();
        foreach ($roles as $role) {
            $data[] = array('value' => $role->name);
        }
        if (count($data)) {
            return $data;
        } else {
            return ['value' => 'No Result Found', 'id' => ''];
        }
    }

    public function prosestambah(Request $request)
    {
        // dd($request->all());
        $nip = $request->nip;
        $role = $request->role;

        $user = User::where('nip', $nip)->first();
        $user->assignRole($role);

        logActivity('default', 'Role')
            // ->performedOn($role)
            ->log("User {$user->name} Has been added Role {$role}");
        return redirect()->back()
            ->with('flash_message', "pegawai telah di proses")
            ->with('flash_type', 'success');
    }
}
