<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Work;
use App\Models\WorkUser;
use Illuminate\Auth\Access\Response;

class WorkUserPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user)
    {
        //
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, WorkUser $workUser)
    {
        //
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user)
    {

    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, WorkUser $workUser)
    {
        $work = Work::find($workUser->work_id);
        if($user->id != $work->user_id){
            return Response::deny('You do not have permission to modify this information.');
        }

        return Response::allow();
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, WorkUser $workUser)
    {
        //
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, WorkUser $workUser)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, WorkUser $workUser)
    {
        //
    }
}
