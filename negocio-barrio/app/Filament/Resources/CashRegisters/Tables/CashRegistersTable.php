<?php

namespace App\Filament\Resources\CashRegisters\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class CashRegistersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('user.name')
                    ->label('Usuario')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('opening_balance')
                    ->label('Saldo de Apertura')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('closing_balance')
                    ->label('Saldo de Cierre')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('expected_total')
                    ->label('Total Esperado')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('difference')
                    ->label('Diferencia')
                    ->numeric(decimalPlaces: 2)
                    ->color(fn (string $state): string => (float)$state < 0 ? 'danger' : 'success')
                    ->weight('bold')
                    ->sortable(),
                TextColumn::make('status')
                    ->label('Estado'),
                TextColumn::make('opened_at')
                    ->label('Abierta el')
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('closed_at')
                    ->label('Cerrada el')
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('created_at')
                    ->label('Creado el')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->label('Actualizado el')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
