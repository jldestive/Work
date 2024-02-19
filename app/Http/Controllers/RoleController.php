<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Http\Requests\StoreRoleRequest;
use App\Http\Requests\UpdateRoleRequest;
use App\Models\Permission;
use App\Models\User;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreRoleRequest $request)
    {
        $role = Role::create([
            'name' => $request->name
        ]);

        $role->load('permissions');

        foreach($request->permissions as $permission){
            $role->permissions()->attach(Permission::where('name', $permission)->first());
        }

        return response()->json([
            'message' => 'Role created successfully',
            'role' => $role
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Role $role)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Role $role)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateRoleRequest $request, Role $role)
    {
        $this->authorize('update', $role);

        $role->update([
            'name' => $request->name
        ]);

        $role->permissions()->detach();
        foreach($request->permissions as $permission){
            $role->permissions()->attach(Permission::where('name', $permission)->first());
        }

        return response()->json([
            'message' => 'Role updated successfully',
            'role' => $role
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Role $role)
    {
        $this->authorize('delete', $role);
        $role->delete();

        return response()->json([
            'message' => 'Role deleted successfully'
        ]);
    }

    public function assignRole(Request $request, Role $role)
    {
        $this->authorize('assignRole', $role);

        $request->validate([
            'user_id' => ['required', 'exists:users,id']
        ]);

        $user = User::find($request->user_id);
        $user->roles()->detach();
        $user->roles()->attach($role);

        return response()->json([
            'message' => 'Role assigned successfully'
        ]);

    }
}
