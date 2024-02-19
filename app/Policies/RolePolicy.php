<?php

namespace App\Policies;

use App\Models\Role;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class RolePolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        //
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Role $role): bool
    {
        //
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        //
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Role $role): Response
    {
        if($role->name === 'admin'){
            return Response::deny('You cannot update the admin role');
        }

        return $user->hasPermission('create-role')
            ? Response::allow()
            : Response::deny('You do not have permission to update a role');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Role $role): Response
    {
        if(!$user->hasPermission('delete-role')){
            return Response::deny('You do not have permission to delete a role');
        }

        if($role->name === 'admin'){
            return Response::deny('You cannot delete the admin role');
        }

        return Response::allow();
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Role $role): bool
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Role $role): bool
    {
        //
    }

    /**
     * Determine whether the user can assign a role to a user.
     */
    public function assignRole(User $user, Role $role): Response
    {
        if(!$user->hasPermission('assign-role')){
            return Response::deny('You do not have permission to assign a role');
        }

        if($role->name === 'admin'){
            return Response::deny('You cannot assign the admin role');
        }

        return Response::allow();
    }
}
