<?php

namespace App\Filament\Widgets;

use Filament\Widgets\TableWidget as BaseWidget;
use App\Models\Product;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;

class LowStockWidget extends BaseWidget
{
    protected static ?string $heading = 'Productos con Bajo Stock';
    protected static ?int $sort = 3;
    protected static ?string $maxContentWidth = 'full';
    protected ?string $pollingInterval = '5s';

    public function getColumnSpan(): int | string | array
    {
        return 'full';
    }

    protected function getTableQuery(): Builder
    {
        return Product::query()
            ->where('quantity', '<=', DB::raw('min_stock'))
            ->where('is_active', true)
            ->orderBy('quantity');
    }

    protected function getTableColumns(): array
    {
        return [
            TextColumn::make('name')
                ->label('Producto')
                ->sortable()
                ->searchable(),
            TextColumn::make('quantity')
                ->label('Stock Actual')
                ->numeric()
                ->color(fn($state) => $state === 0 ? 'danger' : 'warning'),
            TextColumn::make('min_stock')
                ->label('Stock Mínimo')
                ->numeric(),
            TextColumn::make('category.name')
                ->label('Categoría'),
        ];
    }
}
