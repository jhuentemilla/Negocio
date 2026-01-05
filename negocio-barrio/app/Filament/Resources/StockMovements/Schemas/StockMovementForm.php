<?php

namespace App\Filament\Resources\StockMovements\Schemas;

use Filament\Schemas\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;
use App\Models\Product;

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
                            ->required(),
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
