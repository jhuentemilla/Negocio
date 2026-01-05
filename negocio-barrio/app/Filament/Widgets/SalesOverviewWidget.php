<?php

namespace App\Filament\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use App\Models\Sale;
use App\Models\Product;
use Carbon\Carbon;

class SalesOverviewWidget extends BaseWidget
{
    protected static ?int $sort = 1;
    protected static ?string $maxContentWidth = 'full';
    protected ?string $pollingInterval = '5s';

    protected function getStats(): array
    {
        $totalVentas = Sale::sum('total');
        $cantidadVentas = Sale::count();
        $ventasHoy = Sale::whereDate('created_at', today())->sum('total');
        $productosSinStock = Product::where('quantity', 0)->count();

        // Calcular cambio porcentual de ventas hoy vs promedio diario
        $fechaPrimeraVenta = Sale::orderBy('created_at')->first()?->created_at;
        $diasConVentas = $fechaPrimeraVenta ? now()->diffInDays($fechaPrimeraVenta) : 1;
        $promedioDiario = $diasConVentas > 0 ? $totalVentas / $diasConVentas : 0;
        $porcentajeCambio = $promedioDiario > 0 ? (($ventasHoy - $promedioDiario) / $promedioDiario * 100) : 0;

        return [
            Stat::make('Total de Ventas', '$' . number_format($totalVentas, 2))
                ->description('Acumulado total')
                ->icon('heroicon-o-banknotes')
                ->color('success'),

            Stat::make('Ventas Hoy', '$' . number_format($ventasHoy, 2))
                ->description($porcentajeCambio >= 0 ? '↑ ' : '↓ ' . abs(round($porcentajeCambio, 1)) . '% vs promedio')
                ->icon('heroicon-o-arrow-trending-up')
                ->color($porcentajeCambio >= 0 ? 'success' : 'warning'),

            Stat::make('Total de Transacciones', (string)$cantidadVentas)
                ->description('Número de ventas realizadas')
                ->icon('heroicon-o-shopping-cart')
                ->color('info'),

            Stat::make('Productos sin Stock', (string)$productosSinStock)
                ->description('Requieren reabastecimiento')
                ->icon('heroicon-o-exclamation-circle')
                ->color($productosSinStock > 0 ? 'danger' : 'success'),
        ];
    }
}
