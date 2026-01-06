<?php

namespace App\Policies;

use App\Models\Sale;
use App\Models\User;
use App\Services\PermissionService;

class SalePolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return PermissionService::hasAccessToResource('sales');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Sale $sale): bool
    {
        return PermissionService::hasAccessToResource('sales');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return PermissionService::hasPermission($user, 'create_sales');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Sale $sale): bool
    {
        return PermissionService::hasPermission($user, 'update_sales');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Sale $sale): bool
    {
        return PermissionService::hasPermission($user, 'delete_sales');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Sale $sale): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Sale $sale): bool
    {
        return false;
    }
}
