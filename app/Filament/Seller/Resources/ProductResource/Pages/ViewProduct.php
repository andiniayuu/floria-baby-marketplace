<?php

namespace App\Filament\Seller\Resources\ProductResource\Pages;

use App\Filament\Seller\Resources\ProductResource;
use Filament\Resources\Pages\ViewRecord;

class ViewProduct extends ViewRecord
{
    protected static string $resource = ProductResource::class;

    protected static string $view =
    'filament.seller.resources.product-resource.pages.view-product';

    protected function getViewData(): array
    {
        return [
            'record' => $this->record,
            'stock'  => $this->record->stock,
        ];
    }
}
