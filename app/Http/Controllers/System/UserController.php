<?php

namespace App\Http\Controllers\System;

use Auth;
use Notification;
use Illuminate\Http\Request;
use Modules\Core\Notifications\GenericNotification;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\System\Pegawai;
use App\Models\System\Permission;
use App\Models\System\Role;

class UserController extends Controller
{
    public function index(Request $request)
    {
        // return User::with('roles')->take(50)->get();
        $users = User::filtered()
            ->filterRole()
            // ->sortable()
            ->with('roles', 'pegawai')
            ->paginate();

        // logActivity();
        logActivity('default', 'User')->log("Read User Menu");

        return view('system.users.index')->with('users', $users);
    }

    public function create()
    {
        $roles = Role::get();
        $groupedRoles = $roles->split(ceil($roles->count() / 3));

        return view('system.users.create', compact('roles', 'groupedRoles'));
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'nip' => 'required|unique:users|max:20',
        ], [
            'nip.unique' => 'User exists. Please add another.'
        ]);

        $pegawai = Pegawai::find(request('nip'));

        $user = User::create([
            'nip' => request('nip'),
            'name' => $pegawai->nm_peg,
        ]);

        $roles = $request['roles'];

        if (isset($roles)) {
            foreach ($roles as $role) {
                $role_r = Role::where('id', '=', $role)->firstOrFail();
                $user->assignRole($role_r);
            }
        }

        logActivity('default', 'User')
            ->performedOn($user)
            ->log("User {$user->name} has been created");
        return redirect()->route('user.index')
            ->with('flash_message', 'User successfully added.')
            ->with('flash_type', 'success');
    }

    public function show(User $user)
    {
        return view('system.users.show', compact('user'));
    }

    public function edit(User $user)
    {
        $roles = Role::get();
        $groupedRoles = $roles->split(ceil($roles->count() / 3));

        $permissions = Permission::get();
        $groupedPermissions = $permissions->groupBy(function ($item, $key) {
            $dots = explode('.', $item->name);
            return $dots[0];
        });

        return view('system.users.edit', compact('user', 'roles', 'groupedRoles', 'permissions', 'groupedPermissions'));
    }

    public function update(Request $request, User $user)
    {
        $input = $request->only(['nip', 'name', 'email']);

        $rules = [
            'nip' => 'required|max:20',
        ];

        if ($request->get('password')) {
            $rules['password'] = 'min:6|confirmed';
            $input['password'] = $request->password;
        }

        $user->fill($input)->save();

        $roles = $request['roles'];

        if (isset($roles)) {
            $user->roles()->sync($roles);
        } else {
            $wasSysAdmin = $user->hasRole('sysadmin');
            $user->roles()->detach();
            if ($wasSysAdmin) {
                $user->assignRole('sysadmin');
            }
        }

        $permissions = $request['permissions'];

        if (isset($permissions)) {
            $user->syncPermissions($permissions);
        } else {
            $user->permissions()->detach();
        }
        logActivity()
            ->performedOn($user)
            ->log("User {$user->name} has been updated");
        return redirect()->route('user.index')
            ->with('flash_message', 'User successfully edited.')
            ->with('flash_type', 'success');
    }

    public function destroy(User $user)
    {
        $ok = true;

        if ($user->nip == Auth::user()->nip) {
            $ok = false;
            $message = 'You cant delete yourself';
        } elseif ($user->hasRole('sysadmin') && User::role('sysadmin')->count() == 1) {
            $ok = false;
            $message = 'You cant delete the only sysadmin available';
        }

        if (!$ok) {
            return redirect()->route('user.index')
                ->with('flash_message', $message)
                ->with('flash_type', 'danger');
        }

        $user->delete();

        logActivity()
            ->performedOn($user)
            ->log("User {$user->name} has been deleted");

        return redirect()->route('user.index')
            ->with('flash_message', 'User successfully deleted.')
            ->with('flash_type', 'warning');
    }
}
