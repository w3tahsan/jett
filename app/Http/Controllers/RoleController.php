<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleController extends Controller
{
    function role(){
        $permissions = Permission::all();
        $roles = Role::all();
        $users = User::all();
        return view('admin.role.role', [
            'permissions'=>$permissions,
            'roles'=>$roles,
            'users'=>$users,
        ]);
    }
    function add_permission(Request $request){
        Permission::create(['name' => $request->permission]);
        return back();
    }

    function add_role(Request $request){
        $role = Role::create(['name' => $request->role_name]);
        $role->givePermissionTo($request->permission);
        return back();
    }
    function assign_role(Request $request){
        $user = User::find($request->user_id);
        $user->assignRole($request->role_id);
        return back();
    }
    function remove_user_role($user_id){
        $user = User::find($user_id);
        $user->syncRoles([]);
        $user->syncPermissions([]);
        return back();
    }

    function edit_role($role_id){
        $permissions = Permission::all();
        $role_info = Role::find($role_id);
        return view('admin.role.edit_role', [
            'role'=>$role_info,
            'permissions'=>$permissions,
        ]);
    }

    function update_role(Request $request){
        $role = Role::find($request->role_id);
        $role->syncPermissions([$request->permission]);
        return back();
    }
}
