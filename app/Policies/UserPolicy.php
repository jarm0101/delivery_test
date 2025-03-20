<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\Response;

class UserPolicy
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
    public function view(User $auser, User $user): Response
    {
        return $this->onlyAdmin($auser);
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $auser): Response
    {
        return $this->onlyAdmin($auser);
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $auser, User $user): Response
    {
        return $this->onlyAdmin($auser);
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $auser, User $user): Response
    {
        return $this->onlyAdmin($auser);
    }

    private function onlyAdmin(User $user): Response
    {
        return $user->hasRole('admin') ? Response::allow()
            : Response::denyAsNotFound();
    }
}
