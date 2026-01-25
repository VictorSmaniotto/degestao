<?php

namespace App\Policies;

use App\Models\Person;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class PersonPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->isAdmin() || $user->isManager();
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Person $person): bool
    {
        if ($user->isAdmin()) {
            return true;
        }

        // Se for Gestor, pode ver se for subordinado OU se for ele mesmo
        if ($user->isManager()) {
            // Verifica se é subordinado direto ou indireto (por enquanto direto para MVP)
            // Se tivermos hierarquia profunda, precisaria de check recursivo.
            // Para V1: subordinação direta ou ele mesmo.

            // Check se é ele mesmo
            if ($user->person_id === $person->id) {
                return true;
            }

            // Check se é subordinado direto
            return $person->manager_id === $user->person_id;
        }

        // Employee vê a si mesmo
        return $user->person_id === $person->id;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->isAdmin();
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Person $person): bool
    {
        return $user->isAdmin();
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Person $person): bool
    {
        return $user->isAdmin();
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Person $person): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Person $person): bool
    {
        return false;
    }
}
