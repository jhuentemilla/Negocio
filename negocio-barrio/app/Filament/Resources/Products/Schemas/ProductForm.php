<?php

namespace App\Filament\Resources\Products\Schemas;

use App\Models\Category;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class ProductForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Información General')
                    ->columns(2)
                    ->schema([
                        TextInput::make('name')
                            ->label('Nombre')
                            ->required()
                            ->maxLength(255)
                            ->columnSpanFull(),
                        TextInput::make('code')
                            ->label('Código')
                            ->required()
                            ->unique(table: 'products', column: 'code', ignoreRecord: true)
                            ->maxLength(255),
                        TextInput::make('sku')
                            ->label('SKU')
                            ->maxLength(255)
                            ->helperText('Se genera automáticamente según la categoría')
                            ->disabled()
                            ->dehydrated(),
                        Select::make('category_id')
                            ->label('Categoría')
                            ->required()
                            ->options(Category::where('is_active', true)->pluck('name', 'id'))
                            ->live()
                            ->afterStateUpdated(function ($state, $set) {
                                if ($state) {
                                    $category = Category::find($state);
                                    $prefix = $category ? substr(strtoupper($category->name), 0, 3) : 'GEN';
                                    $lastProduct = \App\Models\Product::where('category_id', $state)
                                        ->orderBy('id', 'desc')
                                        ->first();
                                    $nextNumber = ($lastProduct ? (int)substr($lastProduct->sku, -4) : 0) + 1;
                                    $sku = $prefix . str_pad($nextNumber, 4, '0', STR_PAD_LEFT);
                                    $set('sku', $sku);
                                }
                            }),
                    ]),
                Section::make('Detalles del Producto')
                    ->columns(2)
                    ->schema([
                        TextInput::make('price')
                            ->label('Precio')
                            ->required()
                            ->numeric()
                            ->prefix('$')
                            ->step(0.01),
                        TextInput::make('quantity')
                            ->label('Cantidad')
                            ->required()
                            ->numeric()
                            ->default(0),
                        TextInput::make('min_stock')
                            ->label('Stock Mínimo')
                            ->required()
                            ->numeric()
                            ->default(5)
                            ->helperText('Alerta cuando el stock sea igual o menor a este valor'),
                        Toggle::make('is_active')
                            ->label('Activo')
                            ->default(true),
                    ]),
                Section::make('Descripción')
                    ->schema([
                        Textarea::make('description')
                            ->label('Descripción')
                            ->maxLength(65535)
                            ->columnSpanFull(),
                    ]),
            ]);
    }
}
