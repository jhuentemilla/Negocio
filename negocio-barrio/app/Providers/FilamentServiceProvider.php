<?php

namespace App\Providers;

use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\Widgets\AccountWidget;
use Filament\Widgets\FilamentInfoWidget;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use Filament\Navigation\NavigationGroup;
use App\Services\PermissionService;

class FilamentServiceProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->id('admin')
            ->path('admin')
            ->login()
            ->colors([
                'primary' => Color::Amber,
            ])
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\\Filament\\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\\Filament\\Pages')
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\\Filament\\Widgets')
            ->widgets([
                AccountWidget::class,
                FilamentInfoWidget::class,
            ])
            ->navigationGroups([
                NavigationGroup::make()
                    ->label('Negocio'),
                NavigationGroup::make()
                    ->label('Gestión'),
                NavigationGroup::make()
                    ->label('Administración'),
            ])
            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                AuthenticateSession::class,
                ShareErrorsFromSession::class,
                VerifyCsrfToken::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
            ])
            ->authMiddleware([
                Authenticate::class,
            ]);
    }

    private function getFilteredNavigationItems(): array
    {
        // Mapping de recursos a identificadores de navegación
        $resourceMapping = [
            'sales' => 'resources.sales',
            'products' => 'resources.products',
            'categories' => 'resources.categories',
            'stock_movements' => 'resources.stock-movements',
            'users' => 'resources.users',
            'roles' => 'resources.roles',
            'permissions' => 'resources.permissions',
            'sales_reports' => 'pages.sales-reports',
        ];

        $allowedResources = PermissionService::getAllowedResources();
        $items = [];

        foreach ($resourceMapping as $resource => $identifier) {
            if (in_array($resource, $allowedResources)) {
                // El recurso está permitido, se mostrará
                // No necesitamos hacer nada especial
            }
        }

        return $items;
    }
}
