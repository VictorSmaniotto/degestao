<?php

namespace App\Policies;

use App\Models\Context;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class ContextPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->isAdmin();
    }

    public function view(User $user, Context $context): bool
    {
        return $user->isAdmin();
    }

    public function create(User $user): bool
    {
        return $user->isAdmin();
    }

    public function update(User $user, Context $context): bool
    {
        return $user->isAdmin();
    }

    public function delete(User $user, Context $context): bool
    {
        return $user->isAdmin();
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Context $context): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Context $context): bool
    {
        return false;
    }
}
