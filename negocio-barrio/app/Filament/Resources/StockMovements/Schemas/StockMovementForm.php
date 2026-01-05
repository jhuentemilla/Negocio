<?php

namespace App\Filament\Resources\StockMovements\Schemas;

use Filament\Schemas\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;
use App\Models\Product;
use Filament\Schemas\Components\Utilities\Get;

class StockMovementForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Section::make('Ingreso de Stock')
                    ->schema([
                        Select::make('product_id')
                            ->label('Producto')
                            ->options(Product::pluck('name', 'id'))
                            ->searchable()
                            ->required(),
                        Select::make('type')
                            ->label('Tipo de Movimiento')
                            ->options([
                                'entrada' => 'Entrada',
                                'salida' => 'Salida',
                            ])
                            ->default('entrada')
                            ->required()
                            ->live(),
                        Select::make('category')
                            ->label('Categoría')
                            ->options(fn (Get $get) => match($get('type')) {
                                'entrada' => [
                                    'compra' => 'Compra a proveedor',
                                    'devolucion_cliente' => 'Devolución de cliente',
                                    'ajuste' => 'Ajuste de inventario',
                                ],
                                'salida' => [
                                    'perdida' => 'Pérdida/Rotura',
                                    'devolucion_proveedor' => 'Devolución a proveedor',
                                    'ajuste' => 'Ajuste de inventario',
                                ],
                                default => [],
                            })
                            ->visible(fn (Get $get) => filled($get('type')))
                            ->required()
                            ->live(),
                        TextInput::make('quantity')
                            ->label('Cantidad')
                            ->type('number')
                            ->numeric()
                            ->minValue(1)
                            ->required(),
                    ]),
                Section::make('Notas')
                    ->schema([
                        Textarea::make('notes')
                            ->label('Observaciones')
                            ->rows(3),
                    ]),
            ]);
    }
}
