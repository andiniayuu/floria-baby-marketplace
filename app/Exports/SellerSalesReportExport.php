<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithTitle;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;

class SellerSalesReportExport implements
    FromCollection,
    WithHeadings,
    WithMapping,
    WithStyles,
    WithColumnWidths,
    WithTitle,
    WithColumnFormatting
{
    protected $query;
    protected int $rowNumber = 0;

    public function __construct($query)
    {
        $this->query = $query;
    }

    public function collection()
    {
        return $this->query->with(['user', 'items.product'])->get();
    }

    public function title(): string
    {
        return 'Laporan Penjualan';
    }

    public function headings(): array
    {
        return [
            'No',
            'Tanggal',
            'No. Pesanan',
            'Pelanggan',
            'Jumlah Item',
            'Total (Rp)',
            'Status',
        ];
    }

    public function map($order): array
    {
        $this->rowNumber++;

        return [
            $this->rowNumber,
            $order->created_at->format('d-m-Y H:i'),
            $order->order_number,
            $order->user->name ?? '-',
            $order->items->count(),
            $order->grand_total,
            $this->translateStatus($order->status),
        ];
    }

    public function columnFormats(): array
    {
        return [
            'F' => '#,##0',
        ];
    }

    public function columnWidths(): array
    {
        return [
            'A' => 5,
            'B' => 20,
            'C' => 25,
            'D' => 25,
            'E' => 12,
            'F' => 20,
            'G' => 15,
        ];
    }

    public function styles(Worksheet $sheet): array
    {
        return [
            1 => [
                'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
                'fill' => ['fillType' => 'solid', 'startColor' => ['rgb' => '16A34A']],
                'alignment' => ['horizontal' => 'center'],
            ],
        ];
    }

    protected function translateStatus(string $status): string
    {
        return match ($status) {
            'pending'    => 'Menunggu',
            'confirmed'  => 'Dikonfirmasi',
            'processing' => 'Diproses',
            'packed'     => 'Dikemas',
            'shipped'    => 'Dikirim',
            'delivered'  => 'Terkirim',
            'completed'  => 'Selesai',
            'cancelled'  => 'Dibatalkan',
            default      => ucfirst($status),
        };
    }
}
