<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Work;
use Illuminate\Auth\Access\Response;

class WorkPolicy
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
    public function view(User $user, Work $work): bool
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
    public function update(User $user, Work $work): Response
    {
        if($user->id == $work->user_id){
            return Response::allow();
        }

        return Response::deny('You do not own this work.');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Work $work): Response
    {
        return $user->id == $work->user_id ? Response::allow() : Response::deny('You do not own this work');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Work $work): bool
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Work $work): bool
    {
        //
    }
}
