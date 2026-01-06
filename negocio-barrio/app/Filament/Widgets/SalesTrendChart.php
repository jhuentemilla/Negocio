<?php

namespace App\Filament\Widgets;

use Filament\Widgets\ChartWidget;
use App\Models\Sale;
use Flowframe\Trend\Trend;
use Flowframe\Trend\TrendValue;

class SalesTrendChart extends ChartWidget
{
    protected ?string $heading = 'Tendencia de Ventas - Últimos 7 días';
    protected static ?int $sort = 2;
    protected static ?string $maxContentWidth = 'full';
    protected ?string $pollingInterval = '5s'; // Se actualiza cada 5 segundos

    public function getColumnSpan(): int | string | array
    {
        return 'full';
    }

    protected function getData(): array
    {
        // Obtener datos reales de la BD usando Laravel Trend
        $data = Trend::model(Sale::class)
            ->between(
                start: now()->subDays(6),
                end: now(),
            )
            ->perDay()
            ->sum('total');

        // Días en español
        $diasEspanol = ['Dom', 'Lun', 'Mar', 'Mié', 'Jue', 'Vie', 'Sáb'];

        return [
            'datasets' => [
                [
                    'label' => 'Monto de Ventas (CLP)',
                    'data' => $data->map(fn (TrendValue $value) => (int) ($value->aggregate ?? 0)),
                    'borderColor' => '#10b981',
                    'backgroundColor' => 'rgba(16, 185, 129, 0.1)',
                    'borderWidth' => 3,
                    'fill' => true,
                    'tension' => 0.4,
                    'pointRadius' => 5,
                    'pointBackgroundColor' => '#10b981',
                    'pointBorderColor' => '#fff',
                    'pointBorderWidth' => 2,
                    'pointHoverRadius' => 7,
                ],
            ],
            'labels' => $data->map(function(TrendValue $value) use ($diasEspanol) {
                $fecha = \Carbon\Carbon::parse($value->date);
                $dia = $diasEspanol[$fecha->dayOfWeek];
                return $dia . ' ' . $fecha->format('d');
            }),
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }

    protected function getOptions(): array
    {
        return [
            'plugins' => [
                'legend' => [
                    'position' => 'top',
                ],
            ],
            'scales' => [
                'y' => [
                    'type' => 'linear',
                    'display' => true,
                    'position' => 'left',
                    'title' => [
                        'display' => true,
                        'text' => 'Monto (CLP)',
                    ],
                    'ticks' => [
                        'color' => '#6b7280',
                    ],
                ],
            ],
        ];
    }
}
