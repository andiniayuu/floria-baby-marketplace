<?php

namespace App\Policies;

use App\Models\User;

class UserPolicy
{
    /**
     * Create a new policy instance.
     */
    public function accessAdminPanel(User $user): bool
    {
        return $user->role === 'admin';
    }

    /**
     * Determine if user can access Seller Panel
     */
    public function accessSellerPanel(User $user): bool
    {
        return $user->role === 'seller' && $user->seller_status === 'approved';
    }

    // /**
    //  * ====== FILAMENT PERMISSIONS ======
    //  */
    public function viewAny(User $user): bool
    {
        return $user->role === 'admin';
    }

    public function view(User $user, User $model): bool
    {
        return $user->role === 'admin';
    }

    public function create(User $user): bool
    {
        return $user->role === 'admin';
    }

    public function update(User $user, User $model): bool
    {
        return $user->role === 'admin';
    }

    public function delete(User $user, User $model): bool
    {
        return $user->role === 'admin';
    }
}
