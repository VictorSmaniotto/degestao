<?php

namespace App\Policies;

use App\Models\Evidence;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class EvidencePolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->isAdmin() || $user->isManager() || $user->isEmployee();
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Evidence $evidence): bool
    {
        if ($user->isAdmin())
            return true;

        // Se for employee, só vê as suas
        if ($user->isEmployee()) {
            return $evidence->person_id === $user->person_id;
        }

        if ($user->isManager()) {
            // Vê se a evidência é de um subordinado ou dele
            // Carrega a pessoa dona da evidência
            if ($evidence->person_id === $user->person_id)
                return true;

            // Check de subordinação (carregamento lazy ou query, cuidado com N+1)
            // Para simplificar, assumimos que o gestor pode ver evidencias de quem ele gerencia
            return \App\Models\Person::where('id', $evidence->person_id)
                ->where('manager_id', $user->person_id)
                ->exists();
        }

        return false;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->isAdmin() || $user->isManager();
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Evidence $evidence): bool
    {
        // Evidências idealmente são imutáveis, mas permitiremos Admin corrigir.
        // Gestor pode corrigir apenas se foi ele quem criou E for recente?
        // Vamos manter simples: Só Admin corrige.
        return $user->isAdmin();
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Evidence $evidence): bool
    {
        return $user->isAdmin();
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Evidence $evidence): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Evidence $evidence): bool
    {
        return false;
    }
}
