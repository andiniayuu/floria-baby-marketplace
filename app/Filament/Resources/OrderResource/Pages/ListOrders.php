<?php

namespace App\Filament\Resources\OrderResource\Pages;

use App\Filament\Resources\OrderResource;
use App\Filament\Resources\OrderResource\Widgets\OrderStats;
use Filament\Actions;
use Filament\Resources\Components\Tab;
use Filament\Resources\Pages\ListRecords;

class ListOrders extends ListRecords
{
    protected static string $resource = OrderResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }

    protected function getHeaderWidgets(): array
    {
        return [
            OrderStats::class,
        ];
    }

    public function getTabs(): array
    {
        return [
            'all' => Tab::make('All')
                ->modifyQueryUsing(fn($query) => $query),

            'new' => Tab::make('New')
                ->modifyQueryUsing(fn($query) => $query->where('status', 'new')),

            'processing' => Tab::make('Processing')
                ->modifyQueryUsing(fn($query) => $query->where('status', 'processing')),

            'shipped' => Tab::make('Shipped')
                ->modifyQueryUsing(fn($query) => $query->where('status', 'shipped')),

            'delivered' => Tab::make('Delivered')
                ->modifyQueryUsing(fn($query) => $query->where('status', 'delivered')),

            'cancelled' => Tab::make('Cancelled')
                ->modifyQueryUsing(fn($query) => $query->where('status', 'cancelled')),
        ];
    }
}
