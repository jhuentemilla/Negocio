<?php

namespace App\Filament\Resources\Products\Tables;

use Filament\Actions\CreateAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use App\Models\Category;

class ProductsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('code')
                    ->label('Código')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('name')
                    ->label('Nombre')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('category.name')
                    ->label('Categoría')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('price')
                    ->label('Precio')
                    ->money('CLP')
                    ->sortable(),
                BadgeColumn::make('quantity')
                    ->label('Stock')
                    ->colors([
                        'danger' => fn (int $state, $record) => $record->isLowStock(),
                        'warning' => fn (int $state) => $state < 20,
                        'success' => fn (int $state) => $state >= 20,
                    ])
                    ->sortable(),
                TextColumn::make('min_stock')
                    ->label('Stock Mín.')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                IconColumn::make('is_active')
                    ->label('Activo')
                    ->boolean()
                    ->sortable(),
                TextColumn::make('created_at')
                    ->label('Creado')
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                TernaryFilter::make('is_active')
                    ->label('Estado'),
                SelectFilter::make('category_id')
                    ->label('Categoría')
                    ->options(Category::pluck('name', 'id')),
                TernaryFilter::make('low_stock')
                    ->label('Stock Bajo')
                    ->queries(
                        true: fn ($query) => $query->whereColumn('quantity', '<=', 'min_stock'),
                        false: fn ($query) => $query->whereColumn('quantity', '>', 'min_stock'),
                    ),
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
