<?php

namespace App\Exports;

use App\Models\Sale;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class SalesExport implements FromQuery, WithHeadings, WithMapping, WithStyles, WithColumnWidths
{
    public function query()
    {
        return Sale::with(['items', 'user'])->orderBy('created_at', 'desc');
    }

    public function headings(): array
    {
        return [
            'ID',
            'Vendedor',
            'Total (CLP)',
            'Estado',
            'Productos',
            'Fecha',
        ];
    }

    public function map($sale): array
    {
        return [
            $sale->id,
            $sale->user->name ?? 'N/A',
            number_format($sale->total, 2, ',', '.'),
            ucfirst($sale->status),
            $sale->items->count(),
            $sale->created_at->format('d/m/Y H:i'),
        ];
    }

    public function styles(Worksheet $sheet): array
    {
        $sheet->setAutoFilter('A1:F1');

        $headerStyle = [
            'font' => [
                'bold' => true,
                'color' => ['rgb' => 'FFFFFFFF'],
                'size' => 12,
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => 'FF0066CC'],
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER,
            ],
        ];

        return [
            1 => $headerStyle,
        ];
    }

    public function columnWidths(): array
    {
        return [
            'A' => 10,
            'B' => 20,
            'C' => 18,
            'D' => 15,
            'E' => 12,
            'F' => 20,
        ];
    }
}
