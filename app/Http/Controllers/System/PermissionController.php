<?php

namespace App\Http\Controllers\System;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\System\Permission;
use App\Models\System\Role;

class PermissionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $permissions = Permission::orderBy('name')->filtered()->paginate();

        logActivity('default', 'Permission')->log("Read Permission Menu");
        return view('system.permissions.index')->with('permissions', $permissions);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $roles = Role::orderBy('name')->get();
        $groupedRoles = $roles->split(ceil($roles->count() / 4));

        return view('system.permissions.create', compact('roles', 'groupedRoles'));
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
            'name' => 'required|max:50',
        ]);

        $name = $request['name'];
        $permission = new Permission();
        $permission->name = $name;

        $roles = $request['roles'];

        $permission->save();

        if (!empty($request['roles'])) {
            foreach ($roles as $role) {
                $r = Role::where('id', '=', $role)->firstOrFail(); //Match input role to db record

                $permission = Permission::where('name', '=', $name)->first();
                $r->givePermissionTo($permission);
            }
        }

        logActivity('default', 'Permission')
            ->performedOn($permission)
            ->log("Permissions {$permission->name} has been created");
        return redirect()->route('core.permission.index')
            ->with('flash_message', 'Permission' . $permission->name . ' added!')
            ->with('flash_type', 'success');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return redirect('permissions');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $roles = Role::orderBy('name')->get();
        $groupedRoles = $roles->split(ceil($roles->count() / 4));
        $permission = Permission::find($id);

        return view('system.permissions.edit', compact('permission', 'roles', 'groupedRoles'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $permission = Permission::findOrFail($id);

        $this->validate($request, [
            'name' => 'required|max:50',
        ]);

        $input = $request->only('name');
        $permission->fill($input)->save();
        DB::beginTransaction();
        try {
            $permission->syncRoles([]);
            $roles = $request['roles'];
            if (!empty($request['roles'])) {
                foreach ($roles as $role) {
                    $r = Role::where('id', '=', $role)->firstOrFail(); //Match input role to db record
                    $permission = Permission::where('name', '=', $input)->first();
                    $r->givePermissionTo($permission);
                }
            }
            DB::commit();
            logActivity('default', 'Permission')
                ->performedOn($permission)
                ->log("Permissions {$permission->name} has been updated");
        } catch (\Exception $e) {
            DB::rollback();
        }
        return redirect()->route('core.permission.index')
            ->with('flash_message', 'Permission ' . $permission->name . ' telah diperbarui!')
            ->with('flash_type', 'success');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $permission = Permission::findOrFail($id);
        $nama = $permission->name;

        if (substr($permission->name, 0, 5) == "user.") {
            return redirect()->route('permission.index')
                ->with('flash_message', 'Cannot delete this Permission!');
        }

        $permission->delete();
        logActivity('default', 'Permission')
            ->performedOn($permission)
            ->log("Permissions {$permission->name} has been deleted");

        return redirect()->route('permission.index')
            ->with('flash_message', "Permission {$nama} telah dihapus!")
            ->with('flash_type', 'warning');
    }
}
