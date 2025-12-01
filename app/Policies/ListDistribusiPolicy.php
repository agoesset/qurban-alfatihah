<?php

namespace App\Policies;

use App\Models\ListDistribusi;
use App\Models\User;

class ListDistribusiPolicy
{
    /**
     * Determine if the user can view any list distribusis
     */
    public function viewAny(User $user): bool
    {
        // All authenticated users can view the list
        return true;
    }

    /**
     * Determine if the user can view the list distribusi
     */
    public function view(User $user, ListDistribusi $listDistribusi): bool
    {
        // All authenticated users can view a specific distribusi
        return true;
    }

    /**
     * Determine if the user can create list distribusis
     */
    public function create(User $user): bool
    {
        // For now, all authenticated users can create
        // TODO: Implement role-based permissions
        return true;
    }

    /**
     * Determine if the user can update the list distribusi
     */
    public function update(User $user, ListDistribusi $listDistribusi): bool
    {
        // Cannot update if already distributed
        if ($listDistribusi->terdistribusi) {
            return false;
        }

        // For now, all authenticated users can update
        // TODO: Implement role-based permissions
        return true;
    }

    /**
     * Determine if the user can delete the list distribusi
     */
    public function delete(User $user, ListDistribusi $listDistribusi): bool
    {
        // Cannot delete if already distributed
        if ($listDistribusi->terdistribusi) {
            return false;
        }

        // For now, all authenticated users can delete
        // TODO: Implement role-based permissions (e.g., only admin)
        return true;
    }

    /**
     * Determine if the user can permanently delete the list distribusi
     */
    public function forceDelete(User $user, ListDistribusi $listDistribusi): bool
    {
        // Only admins should be able to permanently delete
        // TODO: Implement admin check
        return false; // Disabled for safety
    }

    /**
     * Determine if the user can mark distribusi as packed (terbungkus)
     */
    public function markAsPacked(User $user, ListDistribusi $listDistribusi): bool
    {
        // Cannot pack if already distributed
        if ($listDistribusi->terdistribusi) {
            return false;
        }

        // For now, all authenticated users can mark as packed
        // TODO: Implement role-based permissions (e.g., packaging team)
        return true;
    }

    /**
     * Determine if the user can mark distribusi as distributed (terdistribusi)
     */
    public function markAsDistributed(User $user, ListDistribusi $listDistribusi): bool
    {
        // Cannot distribute if already distributed
        if ($listDistribusi->terdistribusi) {
            return false;
        }

        // Optionally require that it's packed first
        // (This can be enforced in service layer instead)

        // For now, all authenticated users can mark as distributed
        // TODO: Implement role-based permissions (e.g., distribution team)
        return true;
    }
}
