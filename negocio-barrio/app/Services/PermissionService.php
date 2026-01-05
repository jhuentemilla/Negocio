<?php

namespace App\Services;

use App\Models\User;
use App\Models\Permission;
use Illuminate\Support\Facades\Auth;

class PermissionService
{
    /**
     * Obtiene los recursos permitidos para el usuario actual
     */
    public static function getAllowedResources(): array
    {
        $user = Auth::user();
        
        if (!$user) {
            return [];
        }

        // Si el usuario es admin, obtener todos los recursos
        if ($user->roles->where('name', 'Admin')->isNotEmpty()) {
            return [
                'sales',
                'products',
                'categories',
                'stock_movements',
                'users',
                'roles',
                'permissions',
                'sales_reports',
            ];
        }

        // Obtener los recursos permitidos para los roles del usuario
        $allowedResources = [];
        
        foreach ($user->roles as $role) {
            $permissions = $role->permissions()->get();
            
            foreach ($permissions as $permission) {
                if ($permission->resource) {
                    $resources = is_array($permission->resource) 
                        ? $permission->resource 
                        : [$permission->resource];
                    
                    $allowedResources = array_merge($allowedResources, $resources);
                }
            }
        }

        return array_unique($allowedResources);
    }

    /**
     * Verifica si el usuario tiene acceso a un recurso específico
     */
    public static function hasAccessToResource(string $resource): bool
    {
        $allowedResources = self::getAllowedResources();
        return in_array($resource, $allowedResources);
    }
}
