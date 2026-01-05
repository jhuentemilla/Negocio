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
    protected ?string $pollingInterval = '5s'; // Se actualiza cada 5 segundos

    public function getColumnSpan(): int | string | array
    {
        return 'full';
    }

    protected function getData(): array
    {
        // Obtener últimos 7 días incluyendo los días sin ventas
        $endDate = now()->endOfDay();
        $startDate = now()->subDays(6)->startOfDay();
        
        // Nombres de días en español (0=domingo, 1=lunes, etc.)
        $dayNames = ['Dom', 'Lun', 'Mar', 'Mié', 'Jue', 'Vie', 'Sáb'];
        
        // Crear array con todos los días
        $dates = [];
        for ($i = 6; $i >= 0; $i--) {
            $dates[] = now()->subDays($i)->format('Y-m-d');
        }
        
        // Obtener ventas agrupadas por día
        $ventas = Sale::selectRaw('DATE(created_at) as fecha, COUNT(*) as cantidad, SUM(total) as total')
            ->where('created_at', '>=', $startDate)
            ->where('created_at', '<=', $endDate)
            ->groupBy('fecha')
            ->orderBy('fecha')
            ->get()
            ->keyBy('fecha');

        // Llenar datos para todos los días (incluyendo los sin ventas)
        $labels = [];
        $totales = [];
        $cantidades = [];
        
        foreach ($dates as $date) {
            $carbon = Carbon::parse($date);
            $dayOfWeek = $carbon->dayOfWeek;
            $dayName = $dayNames[$dayOfWeek];
            $labels[] = $dayName . ' ' . $carbon->format('d');
            
            if (isset($ventas[$date])) {
                $totales[] = round($ventas[$date]->total, 0);
                $cantidades[] = $ventas[$date]->cantidad;
            } else {
                $totales[] = 0;
                $cantidades[] = 0;
            }
        }

        return [
            'datasets' => [
                [
                    'label' => 'Monto de Ventas (CLP)',
                    'data' => $totales,
                    'borderColor' => '#f59e0b',
                    'backgroundColor' => 'rgba(245, 158, 11, 0.1)',
                    'borderWidth' => 3,
                    'fill' => true,
                    'tension' => 0.4,
                    'pointRadius' => 5,
                    'pointBackgroundColor' => '#f59e0b',
                    'pointBorderColor' => '#fff',
                    'pointBorderWidth' => 2,
                    'pointHoverRadius' => 7,
                ],
                [
                    'label' => 'Cantidad de Ventas',
                    'data' => $cantidades,
                    'borderColor' => '#8b5cf6',
                    'backgroundColor' => 'rgba(139, 92, 246, 0.1)',
                    'borderWidth' => 2,
                    'fill' => false,
                    'tension' => 0.4,
                    'pointRadius' => 4,
                    'pointBackgroundColor' => '#8b5cf6',
                    'yAxisID' => 'y1',
                ],
            ],
            'labels' => $labels,
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
                'y1' => [
                    'type' => 'linear',
                    'display' => true,
                    'position' => 'right',
                    'title' => [
                        'display' => true,
                        'text' => 'Cantidad',
                    ],
                    'ticks' => [
                        'color' => '#8b5cf6',
                    ],
                    'grid' => [
                        'drawOnChartArea' => false,
                    ],
                ],
            ],
        ];
    }
}
