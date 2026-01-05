<?php

namespace App\Filament\Actions;

use App\Exports\SalesExport;
use Filament\Actions\Action;
use Maatwebsite\Excel\Facades\Excel;

class ExportSalesAction extends Action
{
    public static function getDefaultName(): ?string
    {
        return 'export';
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this
            ->label('Exportar')
            ->icon('heroicon-o-arrow-down-tray')
            ->color('gray')
            ->action(function () {
                return Excel::download(
                    new SalesExport(),
                    'ventas_' . now()->format('Y-m-d_H-i-s') . '.xlsx'
                );
            });
    }
}
