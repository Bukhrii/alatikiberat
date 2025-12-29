<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping; 

class InventoryReportExport implements FromCollection, WithHeadings, WithMapping
{
    protected $data;

    public function __construct($data)
    {
        $this->data = $data;
    }

    public function collection()
    {
        return $this->data;
    }

    // Menentukan judul kolom di file Excel
    public function headings(): array
    {
        return ['Kategori', 'Jumlah Item', 'Total Stok', 'Nilai Aset (Rp)'];
    }

    // Memetakan data dari database ke kolom Excel
    public function map($report): array
    {
        return [
            $report->category,
            $report->item_count,
            $report->total_stock,
            $report->total_value
        ];
    }
}
