<?php

namespace App\Policies;

use App\Models\Kategori;
use App\Models\User;

class KategoriPolicy
{
    /**
     * Determine if the user can view any kategoris
     */
    public function viewAny(User $user): bool
    {
        // All authenticated users can view the list
        return true;
    }

    /**
     * Determine if the user can view the kategori
     */
    public function view(User $user, Kategori $kategori): bool
    {
        // All authenticated users can view a specific kategori
        return true;
    }

    /**
     * Determine if the user can create kategoris
     */
    public function create(User $user): bool
    {
        // Only admins should be able to create new kategoris
        // TODO: Implement admin check (e.g., $user->isAdmin())
        // For now, all authenticated users can create
        return true;
    }

    /**
     * Determine if the user can update the kategori
     */
    public function update(User $user, Kategori $kategori): bool
    {
        // Only admins should be able to update kategoris
        // TODO: Implement admin check
        // For now, all authenticated users can update
        return true;
    }

    /**
     * Determine if the user can delete the kategori
     */
    public function delete(User $user, Kategori $kategori): bool
    {
        // Cannot delete kategori if it has associated hewans
        if ($kategori->listHewans()->exists()) {
            return false;
        }

        // Only admins should be able to delete kategoris
        // TODO: Implement admin check
        // For now, all authenticated users can delete
        return true;
    }

    /**
     * Determine if the user can restore the kategori
     */
    public function restore(User $user, Kategori $kategori): bool
    {
        // Only admins should be able to restore kategoris
        // TODO: Implement admin check
        return false; // Kategori doesn't use soft deletes currently
    }

    /**
     * Determine if the user can permanently delete the kategori
     */
    public function forceDelete(User $user, Kategori $kategori): bool
    {
        // Only admins should be able to permanently delete
        // TODO: Implement admin check
        return false; // Disabled for safety
    }
}
