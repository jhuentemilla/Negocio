<?php

namespace App\Filament\Pages;

use App\Models\Sale;
use App\Models\User;
use App\Models\Category;
use Filament\Pages\Page;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\DatePicker;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Illuminate\Database\Eloquent\Builder;
use Filament\Support\Icons\Heroicon;
use BackedEnum;
use App\Services\PermissionService;

class SalesReports extends Page implements HasTable
{
    use InteractsWithTable;

    protected static ?string $navigationLabel = 'Reportes de Ventas';
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedChartBar;
    protected static string|\UnitEnum|null $navigationGroup = 'Gestión';
    protected string $view = 'filament.pages.sales-reports';

    public ?array $filterData = [];

    public static function shouldRegisterNavigation(): bool
    {
        return PermissionService::hasAccessToResource('sales_reports');
    }

    public function filterForm(Schema $schema): Schema
    {
        return $schema
            ->schema([
                Section::make('Filtros de Búsqueda')
                    ->columns(4)
                    ->schema([
                        Select::make('vendor_id')
                            ->label('Vendedor')
                            ->options(User::pluck('name', 'id'))
                            ->searchable()
                            ->preload()
                            ->live(),
                        Select::make('category_id')
                            ->label('Categoría')
                            ->options(Category::pluck('name', 'id'))
                            ->searchable()
                            ->preload()
                            ->live(),
                        DatePicker::make('start_date')
                            ->label('Desde')
                            ->live(),
                        DatePicker::make('end_date')
                            ->label('Hasta')
                            ->live(),
                    ]),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(
                Sale::query()
                    ->with(['user', 'items.product'])
                    ->when($this->filterData['vendor_id'] ?? null, fn (Builder $query) => $query->where('user_id', $this->filterData['vendor_id']))
                    ->when($this->filterData['category_id'] ?? null, function (Builder $query) {
                        return $query->whereHas('items.product', fn (Builder $q) => $q->where('category_id', $this->filterData['category_id']));
                    })
                    ->when($this->filterData['start_date'] ?? null, fn (Builder $query) => $query->whereDate('created_at', '>=', $this->filterData['start_date']))
                    ->when($this->filterData['end_date'] ?? null, fn (Builder $query) => $query->whereDate('created_at', '<=', $this->filterData['end_date']))
                    ->orderBy('created_at', 'desc')
            )
            ->columns([
                TextColumn::make('id')
                    ->label('ID')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('user.name')
                    ->label('Vendedor')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('total')
                    ->label('Total')
                    ->money('CLP')
                    ->sortable(),
                TextColumn::make('items_count')
                    ->label('Productos')
                    ->counts('items'),
                BadgeColumn::make('status')
                    ->label('Estado')
                    ->colors([
                        'success' => 'completed',
                        'danger' => 'cancelled',
                        'warning' => 'pending',
                    ]),
                TextColumn::make('created_at')
                    ->label('Fecha')
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),
            ]);
    }
}
