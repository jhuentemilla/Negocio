<?php

namespace App\Policies;

use App\Models\StockMovement;
use App\Models\User;
use App\Services\PermissionService;

class StockMovementPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return PermissionService::hasAccessToResource('stock_movements');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, StockMovement $stockMovement): bool
    {
        return PermissionService::hasAccessToResource('stock_movements');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        // Los movimientos se crean automáticamente via observadores
        return false;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, StockMovement $stockMovement): bool
    {
        // Los movimientos no se editan, solo se visualizan
        return false;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, StockMovement $stockMovement): bool
    {
        return PermissionService::hasPermission($user, 'delete_stock_movements');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, StockMovement $stockMovement): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, StockMovement $stockMovement): bool
    {
        return false;
    }
}
