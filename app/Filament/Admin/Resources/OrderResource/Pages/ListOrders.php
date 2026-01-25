<?php

namespace App\Filament\Admin\Resources\OrderResource\Pages;

use App\Filament\Admin\Resources\OrderResource;
use Filament\Resources\Components\Tab;
use Filament\Resources\Pages\ListRecords;

class ListOrders extends ListRecords
{
    protected static string $resource = OrderResource::class;

    public function getTabs(): array
    {
        return [
            'all' => Tab::make('Semua'),

            'pending' => Tab::make('Pending')
                ->modifyQueryUsing(
                    fn($query) =>
                    $query->where('status', 'pending')
                ),

            'payment_uploaded' => Tab::make('Bukti Upload')
                ->modifyQueryUsing(
                    fn($query) =>
                    $query->where('status', 'payment_uploaded')
                ),

            'confirmed' => Tab::make('Dikonfirmasi')
                ->modifyQueryUsing(
                    fn($query) =>
                    $query->where('status', 'confirmed')
                ),

            'processing' => Tab::make('Diproses')
                ->modifyQueryUsing(
                    fn($query) =>
                    $query->where('status', 'processing')
                ),

            'packed' => Tab::make('Dikemas')
                ->modifyQueryUsing(
                    fn($query) =>
                    $query->where('status', 'packed')
                ),

            'shipped' => Tab::make('Dikirim')
                ->modifyQueryUsing(
                    fn($query) =>
                    $query->where('status', 'shipped')
                ),

            'delivered' => Tab::make('Sampai')
                ->modifyQueryUsing(
                    fn($query) =>
                    $query->where('status', 'delivered')
                ),

            'completed' => Tab::make('Selesai')
                ->modifyQueryUsing(
                    fn($query) =>
                    $query->where('status', 'completed')
                ),

            'cancelled' => Tab::make('Dibatalkan')
                ->modifyQueryUsing(
                    fn($query) =>
                    $query->where('status', 'cancelled')
                ),

            'rejected' => Tab::make('Ditolak')
                ->modifyQueryUsing(
                    fn($query) =>
                    $query->where('status', 'rejected')
                ),
        ];
    }
}
