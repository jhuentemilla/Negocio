<?php

namespace App\Filament\Pages;

use Filament\Pages\Dashboard as BaseDashboard;
use App\Filament\Widgets\SalesOverviewWidget;
use App\Filament\Widgets\TopProductsWidget;
use App\Filament\Widgets\LowStockWidget;
use App\Filament\Widgets\SalesTrendChart;

class Dashboard extends BaseDashboard
{
    public function getWidgets(): array
    {
        return [
            SalesOverviewWidget::class,
            SalesTrendChart::class,
            LowStockWidget::class,
            TopProductsWidget::class,
        ];
    }
}
