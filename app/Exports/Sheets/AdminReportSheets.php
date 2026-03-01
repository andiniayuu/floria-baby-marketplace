<?php

namespace App\Exports\Sheets;

use App\Models\Order;
use App\Models\User;
use App\Models\Product;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Border;

// ══════════════════════════════════════════════════════════════
// Sheet 1 — Ringkasan
// ══════════════════════════════════════════════════════════════
class AdminSummarySheet implements FromArray, WithTitle, WithStyles, WithColumnWidths
{
    public function __construct(
        protected string $dateFrom,
        protected string $dateTo
    ) {}

    public function title(): string
    {
        return 'Ringkasan';
    }

    public function columnWidths(): array
    {
        return ['A' => 32, 'B' => 22];
    }

    public function array(): array
    {
        $from = $this->dateFrom;
        $to   = $this->dateTo;

        $revenue      = Order::where('payment_status', 'paid')->whereDate('created_at', '>=', $from)->whereDate('created_at', '<=', $to)->sum('grand_total') ?? 0;
        $totalOrders  = Order::whereDate('created_at', '>=', $from)->whereDate('created_at', '<=', $to)->count();
        $paidOrders   = Order::where('payment_status', 'paid')->whereDate('created_at', '>=', $from)->whereDate('created_at', '<=', $to)->count();
        $totalSellers = User::where('role', 'seller')->count();
        $newSellers   = User::where('role', 'seller')->whereDate('created_at', '>=', $from)->whereDate('created_at', '<=', $to)->count();
        $totalBuyers  = User::where('role', 'user')->count();
        $newBuyers    = User::where('role', 'user')->whereDate('created_at', '>=', $from)->whereDate('created_at', '<=', $to)->count();
        $totalProducts = Product::count();
        $avgOrder     = $totalOrders > 0 ? $revenue / $totalOrders : 0;

        return [
            ['LAPORAN PLATFORM FLORIA BABY', ''],
            ['Periode', $from . ' s/d ' . $to],
            ['Digenerate', now()->format('d-m-Y H:i') . ' WIB'],
            ['', ''],
            ['RINGKASAN KEUANGAN', ''],
            ['Total Pendapatan (Lunas)', $revenue],
            ['Total Pesanan', $totalOrders],
            ['Pesanan Lunas', $paidOrders],
            ['Rata-rata Per Pesanan', $avgOrder],
            ['', ''],
            ['DATA PENGGUNA', ''],
            ['Total Seller', $totalSellers],
            ['Seller Baru (Periode Ini)', $newSellers],
            ['Total Pembeli', $totalBuyers],
            ['Pembeli Baru (Periode Ini)', $newBuyers],
            ['Total Produk Terdaftar', $totalProducts],
        ];
    }

    public function styles(Worksheet $sheet): array
    {
        // Judul
        $sheet->mergeCells('A1:B1');
        $sheet->getStyle('A1')->applyFromArray([
            'font' => ['bold' => true, 'size' => 14, 'color' => ['rgb' => 'FFFFFF']],
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'E91E8C']],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
        ]);

        // Section headers
        foreach ([5, 11] as $row) {
            $sheet->mergeCells("A{$row}:B{$row}");
            $sheet->getStyle("A{$row}")->applyFromArray([
                'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
                'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '1F2937']],
            ]);
        }

        // Label kolom A
        $sheet->getStyle('A6:A16')->applyFromArray([
            'font' => ['bold' => true],
        ]);

        // Format currency rows
        foreach ([6, 9] as $row) {
            $sheet->getStyle("B{$row}")->getNumberFormat()->setFormatCode('"Rp "#,##0');
        }

        return [];
    }
}


// ══════════════════════════════════════════════════════════════
// Sheet 2 — Top Seller
// ══════════════════════════════════════════════════════════════
class AdminTopSellersSheet implements FromArray, WithTitle, WithStyles, WithColumnWidths
{
    public function __construct(
        protected string $dateFrom,
        protected string $dateTo
    ) {}

    public function title(): string
    {
        return 'Top Seller';
    }

    public function columnWidths(): array
    {
        return ['A' => 5, 'B' => 28, 'C' => 32, 'D' => 14, 'E' => 22];
    }

    public function array(): array
    {
        $sellers = DB::table('orders')
            ->join('users', 'orders.seller_id', '=', 'users.id')
            ->whereDate('orders.created_at', '>=', $this->dateFrom)
            ->whereDate('orders.created_at', '<=', $this->dateTo)
            ->where('orders.payment_status', 'paid')
            ->select('users.name', 'users.email', DB::raw('COUNT(orders.id) as jumlah_order'), DB::raw('SUM(orders.grand_total) as total_pendapatan'))
            ->groupBy('users.id', 'users.name', 'users.email')
            ->orderByDesc('total_pendapatan')
            ->get();

        $rows = [
            ['#', 'Nama Seller', 'Email', 'Jumlah Order', 'Total Pendapatan (Rp)'],
        ];

        foreach ($sellers as $i => $s) {
            $rows[] = [
                $i + 1,
                $s->name,
                $s->email,
                $s->jumlah_order,
                $s->total_pendapatan,
            ];
        }

        // Total row
        $rows[] = ['', 'TOTAL', '', $sellers->sum('jumlah_order'), $sellers->sum('total_pendapatan')];

        return $rows;
    }

    public function styles(Worksheet $sheet): array
    {
        $lastRow = $sheet->getHighestRow();

        // Header
        $sheet->getStyle('A1:E1')->applyFromArray([
            'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'E91E8C']],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
        ]);

        // Data rows zebra
        for ($r = 2; $r < $lastRow; $r++) {
            if ($r % 2 === 0) {
                $sheet->getStyle("A{$r}:E{$r}")->applyFromArray([
                    'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'FDF2F8']],
                ]);
            }
        }

        // Total row
        $sheet->getStyle("A{$lastRow}:E{$lastRow}")->applyFromArray([
            'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '1F2937']],
        ]);

        // Currency format
        $sheet->getStyle("E2:E{$lastRow}")->getNumberFormat()->setFormatCode('"Rp "#,##0');

        // Center no column
        $sheet->getStyle("A2:A{$lastRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle("D2:D{$lastRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        return [];
    }
}


// ══════════════════════════════════════════════════════════════
// Sheet 3 — Produk Terlaris
// ══════════════════════════════════════════════════════════════
class AdminTopProductsSheet implements FromArray, WithTitle, WithStyles, WithColumnWidths
{
    public function __construct(
        protected string $dateFrom,
        protected string $dateTo
    ) {}

    public function title(): string
    {
        return 'Produk Terlaris';
    }

    public function columnWidths(): array
    {
        return ['A' => 5, 'B' => 36, 'C' => 28, 'D' => 14, 'E' => 22];
    }

    public function array(): array
    {
        $products = DB::table('order_items')
            ->join('orders', 'order_items.order_id', '=', 'orders.id')
            ->join('products', 'order_items.product_id', '=', 'products.id')
            ->join('users as seller', 'products.seller_id', '=', 'seller.id')
            ->whereDate('orders.created_at', '>=', $this->dateFrom)
            ->whereDate('orders.created_at', '<=', $this->dateTo)
            ->where('orders.payment_status', 'paid')
            ->select('products.name', 'seller.name as seller_name', DB::raw('SUM(order_items.quantity) as terjual'), DB::raw('SUM(order_items.quantity * order_items.unit_amount) as pendapatan'))
            ->groupBy('products.id', 'products.name', 'seller.name')
            ->orderByDesc('terjual')
            ->get();

        $rows = [
            ['#', 'Nama Produk', 'Seller', 'Qty Terjual', 'Pendapatan (Rp)'],
        ];

        foreach ($products as $i => $p) {
            $rows[] = [$i + 1, $p->name, $p->seller_name, $p->terjual, $p->pendapatan];
        }

        $rows[] = ['', 'TOTAL', '', $products->sum('terjual'), $products->sum('pendapatan')];

        return $rows;
    }

    public function styles(Worksheet $sheet): array
    {
        $lastRow = $sheet->getHighestRow();

        $sheet->getStyle('A1:E1')->applyFromArray([
            'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'E91E8C']],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
        ]);

        for ($r = 2; $r < $lastRow; $r++) {
            if ($r % 2 === 0) {
                $sheet->getStyle("A{$r}:E{$r}")->applyFromArray([
                    'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'FDF2F8']],
                ]);
            }
        }

        $sheet->getStyle("A{$lastRow}:E{$lastRow}")->applyFromArray([
            'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '1F2937']],
        ]);

        $sheet->getStyle("E2:E{$lastRow}")->getNumberFormat()->setFormatCode('"Rp "#,##0');
        $sheet->getStyle("A2:A{$lastRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle("D2:D{$lastRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        return [];
    }
}


// ══════════════════════════════════════════════════════════════
// Sheet 4 — Semua Pesanan
// ══════════════════════════════════════════════════════════════
class AdminOrdersSheet implements FromArray, WithTitle, WithStyles, WithColumnWidths
{
    public function __construct(
        protected string $dateFrom,
        protected string $dateTo
    ) {}

    public function title(): string
    {
        return 'Pesanan';
    }

    public function columnWidths(): array
    {
        return ['A' => 5, 'B' => 20, 'C' => 22, 'D' => 22, 'E' => 24, 'F' => 20, 'G' => 18, 'H' => 15];
    }

    public function array(): array
    {
        $orders = Order::with(['user', 'seller'])
            ->whereDate('created_at', '>=', $this->dateFrom)
            ->whereDate('created_at', '<=', $this->dateTo)
            ->latest()
            ->get();

        $statusMap = [
            'pending' => 'Menunggu',
            'confirmed' => 'Dikonfirmasi',
            'processing' => 'Diproses',
            'packed' => 'Dikemas',
            'shipped' => 'Dikirim',
            'delivered' => 'Terkirim',
            'completed' => 'Selesai',
            'cancelled' => 'Dibatalkan',
            'payment_uploaded' => 'Bukti Bayar',
            'rejected' => 'Ditolak',
        ];

        $payMap = ['pending' => 'Menunggu', 'paid' => 'Lunas', 'failed' => 'Gagal', 'refunded' => 'Refund'];

        $rows = [
            ['#', 'Tanggal', 'No. Pesanan', 'Pembeli', 'Seller', 'Grand Total (Rp)', 'Status', 'Pembayaran'],
        ];

        foreach ($orders as $i => $order) {
            $rows[] = [
                $i + 1,
                $order->created_at->format('d-m-Y H:i'),
                $order->order_number,
                $order->user?->name ?? '-',
                $order->seller?->name ?? '-',
                $order->grand_total,
                $statusMap[$order->status] ?? ucfirst($order->status),
                $payMap[$order->payment_status] ?? ucfirst($order->payment_status),
            ];
        }

        // Total
        $rows[] = ['', '', '', '', 'TOTAL', $orders->sum('grand_total'), '', ''];

        return $rows;
    }

    public function styles(Worksheet $sheet): array
    {
        $lastRow = $sheet->getHighestRow();

        $sheet->getStyle('A1:H1')->applyFromArray([
            'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '1F2937']],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER],
        ]);

        for ($r = 2; $r < $lastRow; $r++) {
            if ($r % 2 === 0) {
                $sheet->getStyle("A{$r}:H{$r}")->applyFromArray([
                    'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'F9FAFB']],
                ]);
            }
        }

        // Total row
        $sheet->getStyle("A{$lastRow}:H{$lastRow}")->applyFromArray([
            'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => 'E91E8C']],
        ]);

        $sheet->getStyle("F2:F{$lastRow}")->getNumberFormat()->setFormatCode('"Rp "#,##0');
        $sheet->getStyle("A2:A{$lastRow}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

        // Freeze header row
        $sheet->freezePane('A2');

        return [];
    }
}
