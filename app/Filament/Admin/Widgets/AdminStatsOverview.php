<?php

namespace App\Filament\Admin\Widgets;

use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Number;

class AdminStatsOverview extends BaseWidget
{
    protected function getStats(): array
    {
        // Total Revenue
        $totalRevenue = Order::where('payment_status', 'paid')
            ->sum('total_amount');

        // Today Orders
        $todayOrders = Order::whereDate('created_at', today())->count();

        // Products
        $totalProducts = Product::count();
        $outOfStock = Product::where('stock', '<=', 0)->count();

        // Users
        $totalUsers = User::where('role', 'user')->count();
        $totalSellers = User::where('role', 'seller')->count();

        // Pending Orders
        $pendingOrders = Order::where('status', 'payment_uploaded')->count();

        return [
            Stat::make('Total Penjualan', 'Rp ' . Number::format($totalRevenue, locale: 'id'))
                ->description('Total revenue platform')
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->color('success')
                ->chart([7, 2, 10, 3, 15, 4, 17]),

            Stat::make('Pesanan Hari Ini', $todayOrders)
                ->description('Pesanan baru masuk')
                ->descriptionIcon('heroicon-m-shopping-bag')
                ->color('info'),

            Stat::make('Total Produk', $totalProducts)
                ->description($outOfStock . ' produk habis')
                ->descriptionIcon('heroicon-m-cube')
                ->color('warning'),

            Stat::make('Total User', $totalUsers)
                ->description($totalSellers . ' seller aktif')
                ->descriptionIcon('heroicon-m-users')
                ->color('primary'),

            Stat::make('Pesanan Pending', $pendingOrders)
                ->description('Menunggu konfirmasi')
                ->descriptionIcon('heroicon-m-clock')
                ->color('danger'),
        ];
    }
}