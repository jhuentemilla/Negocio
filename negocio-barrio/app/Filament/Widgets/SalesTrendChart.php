<?php

namespace App\Filament\Widgets;

use Filament\Widgets\ChartWidget;
use App\Models\Sale;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class SalesTrendChart extends ChartWidget
{
    protected ?string $heading = 'Tendencia de Ventas - Últimos 7 días';
    protected static ?int $sort = 2;
    protected static ?string $maxContentWidth = 'full';

    public function getColumnSpan(): int | string | array
    {
        return 'full';
    }

    protected function getData(): array
    {
        $ventas = Sale::selectRaw('DATE(created_at) as fecha, COUNT(*) as cantidad, SUM(total) as total')
            ->where('created_at', '>=', now()->subDays(7))
            ->groupBy('fecha')
            ->orderBy('fecha')
            ->get();

        $labels = $ventas->map(fn($v) => Carbon::parse($v->fecha)->format('d/m'))->toArray();
        $datos = $ventas->map(fn($v) => round($v->total, 2))->toArray();

        return [
            'datasets' => [
                [
                    'label' => 'Ventas (CLP)',
                    'data' => $datos,
                    'borderColor' => '#f59e0b',
                    'backgroundColor' => 'rgba(245, 158, 11, 0.1)',
                    'borderWidth' => 2,
                    'fill' => true,
                    'tension' => 0.4,
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}
