<?php

namespace App\Policies;

use App\Models\ListHewan;
use App\Models\User;

class ListHewanPolicy
{
    /**
     * Determine if the user can view any list hewans
     */
    public function viewAny(User $user): bool
    {
        // All authenticated users can view the list
        return true;
    }

    /**
     * Determine if the user can view the list hewan
     */
    public function view(User $user, ListHewan $listHewan): bool
    {
        // All authenticated users can view a specific hewan
        return true;
    }

    /**
     * Determine if the user can create list hewans
     */
    public function create(User $user): bool
    {
        // For now, all authenticated users can create
        // TODO: Implement role-based permissions (e.g., only admin and staff)
        return true;
    }

    /**
     * Determine if the user can update the list hewan
     */
    public function update(User $user, ListHewan $listHewan): bool
    {
        // For now, all authenticated users can update
        // TODO: Implement role-based permissions
        // Prevent update if hewan is soft deleted
        return !$listHewan->trashed();
    }

    /**
     * Determine if the user can delete the list hewan
     */
    public function delete(User $user, ListHewan $listHewan): bool
    {
        // For now, all authenticated users can delete
        // TODO: Implement role-based permissions (e.g., only admin)
        // Prevent deletion if already deleted
        return !$listHewan->trashed();
    }

    /**
     * Determine if the user can restore the list hewan
     */
    public function restore(User $user, ListHewan $listHewan): bool
    {
        // For now, all authenticated users can restore
        // TODO: Implement role-based permissions (e.g., only admin)
        // Can only restore if soft deleted
        return $listHewan->trashed();
    }

    /**
     * Determine if the user can permanently delete the list hewan
     */
    public function forceDelete(User $user, ListHewan $listHewan): bool
    {
        // Only admins should be able to permanently delete
        // TODO: Implement admin check (e.g., $user->isAdmin())
        return false; // Disabled for safety
    }

    /**
     * Determine if the user can update workflow status (penyembelihan, pengulitan, penimbangan)
     */
    public function updateWorkflowStatus(User $user, ListHewan $listHewan): bool
    {
        // For now, all authenticated users can update workflow
        // TODO: Implement role-based permissions
        // Prevent update if hewan is soft deleted
        return !$listHewan->trashed();
    }
}
