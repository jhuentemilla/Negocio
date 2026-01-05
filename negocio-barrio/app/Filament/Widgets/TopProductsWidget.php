<?php

namespace App\Filament\Widgets;

use Filament\Widgets\TableWidget as BaseWidget;
use App\Models\SaleItem;
use Illuminate\Database\Eloquent\Builder;
use Filament\Tables\Columns\TextColumn;

class TopProductsWidget extends BaseWidget
{
    protected static ?string $heading = 'Top 5 Productos Vendidos - Últimos 30 días';
    protected static ?int $sort = 4;
    protected static ?string $maxContentWidth = 'full';
    protected ?string $pollingInterval = '5s';

    public function getColumnSpan(): int | string | array
    {
        return 'full';
    }

    protected function getTableQuery(): Builder
    {
        return SaleItem::query()
            ->with('product')
            ->where('created_at', '>=', now()->subDays(30))
            ->selectRaw('product_id, SUM(quantity) as cantidad_vendida, SUM(subtotal) as total_vendido')
            ->groupBy('product_id')
            ->orderByDesc('cantidad_vendida')
            ->limit(5);
    }

    protected function getTableColumns(): array
    {
        return [
            TextColumn::make('product.name')
                ->label('Producto')
                ->sortable(),
            TextColumn::make('cantidad_vendida')
                ->label('Cantidad Vendida')
                ->numeric(),
            TextColumn::make('total_vendido')
                ->label('Total Vendido')
                ->money('CLP')
                ->sortable(),
        ];
    }
}
