<?php

namespace App\Filament\Resources\CashRegisters;

use App\Filament\Resources\CashRegisters\Pages\CreateCashRegister;
use App\Filament\Resources\CashRegisters\Pages\EditCashRegister;
use App\Filament\Resources\CashRegisters\Pages\ListCashRegisters;
use App\Filament\Resources\CashRegisters\Schemas\CashRegisterForm;
use App\Filament\Resources\CashRegisters\Tables\CashRegistersTable;
use App\Models\CashRegister;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use App\Services\PermissionService;

class CashRegisterResource extends Resource
{
    protected static ?string $model = CashRegister::class;

    protected static ?string $navigationLabel = 'Caja';
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedCurrencyDollar;
    protected static string|\UnitEnum|null $navigationGroup = 'Administración';
    protected static ?int $navigationSort = 4;

    public static function shouldRegisterNavigation(): bool
    {
        return PermissionService::hasAccessToResource('cash_registers');
    }

    public static function form(Schema $schema): Schema
    {
        return CashRegisterForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return CashRegistersTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListCashRegisters::route('/'),
            'create' => CreateCashRegister::route('/create'),
            'edit' => EditCashRegister::route('/{record}/edit'),
        ];
    }
}
