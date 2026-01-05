<?php

namespace App\Filament\Resources\CashRegisters\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class CashRegisterForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('user_id')
                    ->label('Usuario')
                    ->relationship('user', 'name')
                    ->required(),
                TextInput::make('opening_balance')
                    ->label('Saldo de Apertura')
                    ->required()
                    ->numeric()
                    ->default(0.0),
                TextInput::make('closing_balance')
                    ->label('Saldo de Cierre')
                    ->numeric()
                    ->default(null),
                TextInput::make('expected_total')
                    ->label('Total Esperado')
                    ->numeric()
                    ->default(null),
                TextInput::make('difference')
                    ->label('Diferencia')
                    ->numeric()
                    ->default(null),
                Select::make('status')
                    ->label('Estado')
                    ->options(['open' => 'Abierta', 'closed' => 'Cerrada'])
                    ->default('open')
                    ->required(),
                Textarea::make('notes')
                    ->label('Notas')
                    ->default(null)
                    ->columnSpanFull(),
                DateTimePicker::make('opened_at')
                    ->label('Abierta el')
                    ->required(),
                DateTimePicker::make('closed_at')
                    ->label('Cerrada el'),
            ]);
    }
}
