<?php

namespace App\Filament\Seller\Widgets;

use App\Models\Order;
use App\Models\Product;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Number;

class SellerStatsOverview extends BaseWidget
{
    protected function getStats(): array
    {
        $sellerId = auth()->id();

        // Total Revenue
        $totalRevenue = Order::where('payment_status', 'paid')
            ->whereHas('items', function ($query) use ($sellerId) {
                $query->whereHas('product', function ($q) use ($sellerId) {
                    $q->where('seller_id', $sellerId);
                });
            })
            ->sum('total_amount');

        // Pending Orders
        $pendingOrders = Order::whereIn('status', ['confirmed', 'processing'])
            ->whereHas('items', function ($query) use ($sellerId) {
                $query->whereHas('product', function ($q) use ($sellerId) {
                    $q->where('seller_id', $sellerId);
                });
            })
            ->count();

        // Products
        $totalProducts = Product::where('seller_id', $sellerId)->count();
        $outOfStock = Product::where('seller_id', $sellerId)
            ->where('stock', '<=', 0)
            ->count();

        // Products Sold
        $productsSold = Order::where('payment_status', 'paid')
            ->whereHas('items', function ($query) use ($sellerId) {
                $query->whereHas('product', function ($q) use ($sellerId) {
                    $q->where('seller_id', $sellerId);
                });
            })
            ->withSum('items', 'quantity')
            ->get()
            ->sum('items_sum_quantity') ?? 0;

        return [
            Stat::make('Total Penjualan', 'Rp ' . Number::format($totalRevenue, locale: 'id'))
                ->description('Total pendapatan toko')
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->color('success')
                ->chart([5, 10, 7, 15, 12, 20, 18]),

            Stat::make('Pesanan Masuk', $pendingOrders)
                ->description('Perlu diproses')
                ->descriptionIcon('heroicon-m-shopping-bag')
                ->color('warning'),

            Stat::make('Produk Saya', $totalProducts)
                ->description($outOfStock . ' produk habis stok')
                ->descriptionIcon('heroicon-m-cube')
                ->color('info'),

            Stat::make('Produk Terjual', $productsSold)
                ->description('Total item terjual')
                ->descriptionIcon('heroicon-m-chart-bar')
                ->color('primary'),
        ];
    }
}