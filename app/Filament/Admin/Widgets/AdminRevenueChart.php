<?php

namespace App\Filament\Admin\Widgets;

use App\Models\Order;
use Filament\Widgets\ChartWidget;
use Flowframe\Trend\Trend;
use Flowframe\Trend\TrendValue;

class AdminRevenueChart extends ChartWidget
{
    protected static ?string $heading = 'Revenue 30 Hari Terakhir';
    
    protected static ?int $sort = 2;

    protected function getData(): array
    {
        $data = Trend::query(Order::where('payment_status', 'paid'))
            ->between(
                start: now()->subDays(30),
                end: now(),
            )
            ->perDay()
            ->sum('total_amount');

        return [
            'datasets' => [
                [
                    'label' => 'Revenue (Rp)',
                    'data' => $data->map(fn (TrendValue $value) => $value->aggregate),
                    'backgroundColor' => 'rgba(59, 130, 246, 0.1)',
                    'borderColor' => 'rgb(59, 130, 246)',
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