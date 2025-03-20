<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Worker;
use Illuminate\Auth\Access\Response;

class WorkerPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): Response
    {
        return $user->hasRole('admin') ? Response::allow()
            : Response::denyAsNotFound();
    }

    public function view(?User $user, Worker $worker): Response
    {
        if ($user->hasRole('admin') || $worker->user_id == $user->id) {
            return Response::allow();
        }

        return Response::denyAsNotFound();
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): Response
    {
        return $user->hasRole('admin') ? Response::allow()
            : Response::denyAsNotFound();
    }


    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user): Response
    {
        return $user->hasRole('admin') ? Response::allow()
            : Response::denyAsNotFound();
    }
}
