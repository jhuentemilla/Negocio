<?php

namespace App\Filament\Resources\Sales\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Hidden;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Facades\Filament;

class SaleForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Hidden::make('user_id')
                    ->default(fn () => Filament::auth()->id())
                    ->required(),
                Section::make('Información de Venta')
                    ->columns(2)
                    ->schema([
                        DateTimePicker::make('sold_at')
                            ->label('Fecha y Hora')
                            ->required()
                            ->default(now()),
                        TextInput::make('total')
                            ->label('Total')
                            ->required()
                            ->numeric()
                            ->prefix('$')
                            ->readOnly()
                            ->columnSpanFull(),
                    ])
                    ->columnSpanFull(),
                Section::make('Productos')
                    ->schema([
                        Repeater::make('items')
                            ->relationship()
                            ->columns(5)
                            ->schema([
                                Select::make('product_id')
                                    ->label('Producto')
                                    ->required()
                                    ->options(fn () => \App\Models\Product::where('is_active', true)->pluck('name', 'id'))
                                    ->searchable()
                                    ->live()
                                    ->columnSpan(2)
                                    ->helperText('Busca por nombre')
                                    ->afterStateUpdated(function ($state, $set) {
                                        if ($state) {
                                            $product = \App\Models\Product::find($state);
                                            if ($product) {
                                                $set('unit_price', $product->price);
                                                $set('subtotal', 0);
                                            }
                                        }
                                    }),
                                TextInput::make('quantity')
                                    ->label('Cantidad')
                                    ->required()
                                    ->numeric()
                                    ->minValue(1)
                                    ->columnSpan(1)
                                    ->live()
                                    ->helperText('Stock disponible')
                                    ->afterStateUpdated(function ($state, $get, $set) {
                                        $price = $get('unit_price') ?? 0;
                                        $set('subtotal', $state * $price);
                                    }),
                                TextInput::make('unit_price')
                                    ->label('Precio')
                                    ->required()
                                    ->numeric()
                                    ->prefix('$')
                                    ->columnSpan(1)
                                    ->readOnly()
                                    ->live()
                                    ->afterStateUpdated(function ($state, $get, $set) {
                                        $qty = $get('quantity') ?? 0;
                                        $set('subtotal', $qty * $state);
                                    }),
                                TextInput::make('subtotal')
                                    ->label('Subtotal')
                                    ->required()
                                    ->numeric()
                                    ->prefix('$')
                                    ->columnSpan(1)
                                    ->readOnly(),
                            ])
                            ->minItems(1)
                            ->addActionLabel('+ Agregar Producto')
                            ->columnSpanFull(),
                    ])
                    ->columnSpanFull(),
                Section::make('Notas')
                    ->schema([
                        Textarea::make('notes')
                            ->label('Observaciones')
                            ->columnSpanFull(),
                    ])
                    ->columnSpanFull(),
            ]);
    }
}
