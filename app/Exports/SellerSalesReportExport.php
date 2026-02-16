<?php

namespace App\Exports;

use App\Models\Order;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class SellerSalesReportExport implements FromCollection, WithHeadings, WithMapping
{
    protected $query;

    public function __construct($query)
    {
        $this->query = $query;
    }

    public function collection()
    {
        return $this->query->with('user')->get();
    }

    public function headings(): array
    {
        return [
            'Tanggal',
            'No Order',
            'Pelanggan',
            'Total',
            'Status',
        ];
    }

    public function map($order): array
    {
        return [
            $order->created_at->format('d-m-Y'),
            $order->order_number,
            $order->user->name ?? '-',
            $order->total_amount,
            $order->status,
        ];
    }
}
