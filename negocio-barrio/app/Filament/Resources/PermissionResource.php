<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PermissionResource\Pages;
use App\Models\Permission;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Select;
use Filament\Schemas\Components\Section;
use Filament\Tables\Columns\TextColumn;
use Filament\Actions\EditAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Support\Icons\Heroicon;
use Filament\Actions\CreateAction;
use BackedEnum;
use App\Services\PermissionService;

class PermissionResource extends Resource
{
    protected static ?string $model = Permission::class;

    protected static ?string $navigationLabel = 'Permisos';

    protected static string|\UnitEnum|null $navigationGroup = 'Configuración';

    protected static ?int $navigationSort = 99;

    protected static ?string $pluralModelLabel = 'Permisos';

    protected static ?string $modelLabel = 'Permiso';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedShieldExclamation;

    public static function shouldRegisterNavigation(): bool
    {
        return PermissionService::hasAccessToResource('permissions');
    }

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Información del Permiso')
                    ->columns(2)
                    ->schema([
                        TextInput::make('name')
                            ->label('Nombre del Permiso')
                            ->required()
                            ->maxLength(255)
                            ->unique(ignoreRecord: true)
                            ->helperText('Ej: view_sales, edit_products'),
                        Select::make('resource')
                            ->label('Recurso/Vista')
                            ->options([
                                'sales' => 'Ventas',
                                'products' => 'Productos',
                                'categories' => 'Categorías',
                                'stock_movements' => 'Movimientos de Stock',
                                'users' => 'Usuarios',
                                'roles' => 'Roles',
                                'permissions' => 'Permisos',
                                'sales_reports' => 'Reportes de Ventas',
                                'cash_registers' => 'Caja',
                            ])
                            ->multiple()
                            ->searchable()
                            ->required(),
                        Textarea::make('description')
                            ->label('Descripción')
                            ->maxLength(255)
                            ->columnSpanFull()
                            ->helperText('Describe brevemente qué permite este permiso'),
                        Select::make('roles')
                            ->label('Roles con este Permiso')
                            ->relationship('roles', 'name')
                            ->multiple()
                            ->preload()
                            ->columnSpanFull(),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')
                    ->label('ID')
                    ->sortable(),
                TextColumn::make('name')
                    ->label('Nombre')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('resource')
                    ->label('Recurso')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('description')
                    ->label('Descripción')
                    ->searchable()
                    ->wrap(),
                TextColumn::make('roles.name')
                    ->label('Roles')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'Admin' => 'danger',
                        'Vendedor' => 'success',
                        default => 'gray',
                    }),
                TextColumn::make('created_at')
                    ->label('Creado')
                    ->dateTime('d/m/Y')
                    ->sortable(),
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ])
            ->paginated([10, 25, 50]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPermissions::route('/'),
            'create' => Pages\CreatePermission::route('/create'),
            'edit' => Pages\EditPermission::route('/{record}/edit'),
        ];
    }
}
