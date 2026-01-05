<?php

namespace App\Filament\Traits;

use App\Services\PermissionService;

trait HasPermissionGuard
{
    /**
     * Determina si el recurso debe ser visible basado en permisos
     */
    public static function isVisibleInNavigation(): bool
    {
        $resource = static::getResourceIdentifier();
        return PermissionService::hasAccessToResource($resource);
    }

    /**
     * Obtiene el identificador del recurso para permisos
     */
    protected static function getResourceIdentifier(): string
    {
        return '';
    }
}
