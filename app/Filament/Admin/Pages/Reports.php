<?php

namespace App\Filament\Admin\Pages;

use Filament\Pages\Page;
use Filament\Actions\Action;
use App\Models\Order;
use App\Models\User;
use App\Models\Product;
use Illuminate\Support\Facades\DB;

class Reports extends Page
{
    protected static ?string $navigationIcon  = 'heroicon-o-document-text';
    protected static ?string $navigationLabel = 'Laporan';
    protected static ?string $title           = 'Laporan Platform';
    protected static string  $view            = 'filament.admin.pages.reports';

    public string $period   = 'this_month';
    public string $dateFrom = '';
    public string $dateTo   = '';

    public function mount(): void
    {
        $this->dateFrom = now()->startOfMonth()->format('Y-m-d');
        $this->dateTo   = now()->format('Y-m-d');
    }

    public function updatedPeriod(): void
    {
        [$this->dateFrom, $this->dateTo] = match ($this->period) {
            'today'      => [now()->toDateString(), now()->toDateString()],
            'this_week'  => [now()->startOfWeek()->toDateString(), now()->toDateString()],
            'this_month' => [now()->startOfMonth()->toDateString(), now()->toDateString()],
            'last_month' => [now()->subMonth()->startOfMonth()->toDateString(), now()->subMonth()->endOfMonth()->toDateString()],
            'this_year'  => [now()->startOfYear()->toDateString(), now()->toDateString()],
            default      => [$this->dateFrom, $this->dateTo],
        };
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('export_pdf')
                ->label('Export PDF')
                ->icon('heroicon-o-document-arrow-down')
                ->color('danger')
                ->action(function () {
                    $data = $this->getViewData();

                    return response()->streamDownload(function () use ($data) {
                        echo \Barryvdh\DomPDF\Facade\Pdf::loadView(
                            'filament.admin.pages.admin-report-pdf',
                            $data
                        )
                        ->setPaper('a4', 'landscape')
                        ->output();
                    }, 'laporan-platform-' . now()->format('Y-m-d') . '.pdf');
                }),
        ];
    }

    private function range($query, string $col = 'created_at')
    {
        return $query
            ->whereDate($col, '>=', $this->dateFrom)
            ->whereDate($col, '<=', $this->dateTo);
    }

    protected function getViewData(): array
    {
        $from = $this->dateFrom;
        $to   = $this->dateTo;

        $totalRevenue  = $this->range(Order::where('payment_status', 'paid'))->sum('grand_total') ?? 0;
        $totalOrders   = $this->range(Order::query())->count();
        $paidOrders    = $this->range(Order::where('payment_status', 'paid'))->count();
        $totalSellers  = User::where('role', 'seller')->count();
        $totalBuyers   = User::where('role', 'user')->count();
        $totalProducts = Product::count();
        $newSellers    = $this->range(User::where('role', 'seller'))->count();
        $newBuyers     = $this->range(User::where('role', 'user'))->count();
        $avgOrder      = $totalOrders > 0 ? $totalRevenue / $totalOrders : 0;

        $orderStatus = $this->range(Order::query())
            ->select('status', DB::raw('COUNT(*) as total'))
            ->groupBy('status')
            ->get()
            ->keyBy('status');

        $topSellers = DB::table('orders')
            ->join('users', 'orders.seller_id', '=', 'users.id')
            ->whereDate('orders.created_at', '>=', $from)
            ->whereDate('orders.created_at', '<=', $to)
            ->where('orders.payment_status', 'paid')
            ->select(
                'users.id', 'users.name', 'users.email',
                DB::raw('COUNT(orders.id) as jumlah_order'),
                DB::raw('SUM(orders.grand_total) as total_pendapatan')
            )
            ->groupBy('users.id', 'users.name', 'users.email')
            ->orderByDesc('total_pendapatan')
            ->limit(8)
            ->get();

        $topProducts = DB::table('order_items')
            ->join('orders', 'order_items.order_id', '=', 'orders.id')
            ->join('products', 'order_items.product_id', '=', 'products.id')
            ->join('users as seller', 'products.seller_id', '=', 'seller.id')
            ->whereDate('orders.created_at', '>=', $from)
            ->whereDate('orders.created_at', '<=', $to)
            ->where('orders.payment_status', 'paid')
            ->select(
                'products.id', 'products.name', 'seller.name as seller_name',
                DB::raw('SUM(order_items.quantity) as terjual'),
                DB::raw('SUM(order_items.quantity * order_items.unit_amount) as pendapatan')
            )
            ->groupBy('products.id', 'products.name', 'seller.name')
            ->orderByDesc('terjual')
            ->limit(8)
            ->get();

        $recentOrders = $this->range(Order::with(['user', 'seller'])->latest())
            ->limit(10)
            ->get();

        $newRegistrants = $this->range(User::whereIn('role', ['user', 'seller'])->latest())
            ->limit(8)
            ->get();

        return [
            'totalRevenue'   => $totalRevenue,
            'totalOrders'    => $totalOrders,
            'paidOrders'     => $paidOrders,
            'totalSellers'   => $totalSellers,
            'totalBuyers'    => $totalBuyers,
            'totalProducts'  => $totalProducts,
            'newSellers'     => $newSellers,
            'newBuyers'      => $newBuyers,
            'avgOrder'       => $avgOrder,
            'orderStatus'    => $orderStatus,
            'topSellers'     => $topSellers,
            'topProducts'    => $topProducts,
            'recentOrders'   => $recentOrders,
            'newRegistrants' => $newRegistrants,
            'dateFrom'       => $from,
            'dateTo'         => $to,
        ];
    }
}