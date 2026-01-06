<?php

namespace App\Policies;

use App\Models\CashRegister;
use App\Models\User;
use App\Services\PermissionService;

class CashRegisterPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return PermissionService::hasAccessToResource('cash_registers');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, CashRegister $cashRegister): bool
    {
        return PermissionService::hasAccessToResource('cash_registers');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return PermissionService::hasPermission($user, 'create_cash_registers');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, CashRegister $cashRegister): bool
    {
        return PermissionService::hasPermission($user, 'update_cash_registers');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, CashRegister $cashRegister): bool
    {
        return PermissionService::hasPermission($user, 'delete_cash_registers');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, CashRegister $cashRegister): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, CashRegister $cashRegister): bool
    {
        return false;
    }
}
