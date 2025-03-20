<?php

namespace App\Policies;

use App\Models\Delivery;
use App\Models\User;
use Illuminate\Auth\Access\Response;


class DeliveryPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return true;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Delivery $delivery): Response
    {
        if ($user->hasRole('admin') || $delivery->provider->user_id == $user->id || $delivery->worker->user_id == $user->id) {
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
     * Determine whether the user can update the model.
     */
    public function update(User $user, Delivery $delivery): Response
    {
        return $user->hasRole('admin') || $user->hasRole('provider') ? Response::allow()
            : Response::denyAsNotFound();
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Delivery $delivery): Response
    {
        return $user->hasRole('admin') ? Response::allow()
            : Response::denyAsNotFound();
    }

    public function export(User $user): Response
    {
        return $user->hasRole('admin') ? Response::allow()
            : Response::denyAsNotFound();
    }
}
