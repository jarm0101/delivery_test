<?php

namespace App\Policies;

use Illuminate\Auth\Access\Response;
use App\Models\User;
use App\Models\VehicleType;

class VehicleTypePolicy
{
    /**
     * Create a new policy instance.
     */
    public function viewAny(User $user): Response
    {
        return $this->onlyAdmin($user);
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, VehicleType $vehicle): Response
    {
        return $this->onlyAdmin($user);
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): Response
    {
        return $this->onlyAdmin($user);
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, VehicleType $vehicle): Response
    {
        return $this->onlyAdmin($user);
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, VehicleType $vehicle): Response
    {
        return $this->onlyAdmin($user);
    }

    private function onlyAdmin(User $user): Response
    {
        return $user->hasRole('admin') ? Response::allow()
            : Response::denyAsNotFound();
    }
}
