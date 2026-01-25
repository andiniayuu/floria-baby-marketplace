<?php

namespace App\Filament\Seller\Widgets;

use App\Models\Order;
use Filament\Widgets\ChartWidget;
use Flowframe\Trend\Trend;
use Flowframe\Trend\TrendValue;

class SellerRevenueChart extends ChartWidget
{
    protected static ?string $heading = 'Penjualan 30 Hari Terakhir';
    
    protected static ?int $sort = 2;

    protected function getData(): array
    {
        $sellerId = auth()->id();

        $query = Order::where('payment_status', 'paid')
            ->whereHas('items', function ($q) use ($sellerId) {
                $q->whereHas('product', function ($q2) use ($sellerId) {
                    $q2->where('seller_id', $sellerId);
                });
            });

        $data = Trend::query($query)
            ->between(
                start: now()->subDays(30),
                end: now(),
            )
            ->perDay()
            ->sum('total_amount');

        return [
            'datasets' => [
                [
                    'label' => 'Penjualan (Rp)',
                    'data' => $data->map(fn (TrendValue $value) => $value->aggregate),
                    'backgroundColor' => 'rgba(251, 191, 36, 0.1)',
                    'borderColor' => 'rgb(251, 191, 36)',
                    'fill' => true,
                ],
            ],
            'labels' => $data->map(fn (TrendValue $value) => date('d M', strtotime($value->date))),
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}