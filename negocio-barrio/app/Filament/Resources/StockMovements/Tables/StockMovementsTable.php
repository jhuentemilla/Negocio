<?php

namespace App\Filament\Resources\StockMovements\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use App\Models\Product;

class StockMovementsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('product.code')
                    ->label('Código')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('product.name')
                    ->label('Producto')
                    ->searchable()
                    ->sortable(),
                BadgeColumn::make('type')
                    ->label('Tipo')
                    ->colors([
                        'success' => 'entrada',
                        'danger' => 'salida',
                    ])
                    ->formatStateUsing(fn ($state) => match($state) {
                        'entrada' => 'Entrada',
                        'salida' => 'Salida',
                        default => $state,
                    }),
                TextColumn::make('category')
                    ->label('Categoría')
                    ->formatStateUsing(fn ($state) => match($state) {
                        'compra' => 'Compra a proveedor',
                        'devolucion_cliente' => 'Devolución de cliente',
                        'perdida' => 'Pérdida/Rotura',
                        'devolucion_proveedor' => 'Devolución a proveedor',
                        'ajuste' => 'Ajuste de inventario',
                        default => $state,
                    })
                    ->badge()
                    ->colors([
                        'info' => 'compra',
                        'warning' => fn ($state) => in_array($state, ['devolucion_cliente', 'devolucion_proveedor']),
                        'danger' => 'perdida',
                        'gray' => 'ajuste',
                    ]),
                TextColumn::make('quantity')
                    ->label('Cantidad')
                    ->sortable(),
                TextColumn::make('notes')
                    ->label('Notas')
                    ->limit(50),
                TextColumn::make('created_at')
                    ->label('Fecha')
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('product_id')
                    ->label('Producto')
                    ->options(Product::pluck('name', 'id')),
                SelectFilter::make('type')
                    ->label('Tipo')
                    ->options([
                        'entrada' => 'Entrada',
                        'salida' => 'Salida',
                    ]),
                SelectFilter::make('category')
                    ->label('Categoría')
                    ->options([
                        'compra' => 'Compra a proveedor',
                        'devolucion_cliente' => 'Devolución de cliente',
                        'perdida' => 'Pérdida/Rotura',
                        'devolucion_proveedor' => 'Devolución a proveedor',
                        'ajuste' => 'Ajuste de inventario',
                    ]),
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
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
