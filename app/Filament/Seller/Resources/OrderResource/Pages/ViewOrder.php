<?php

namespace App\Filament\Seller\Resources\OrderResource\Pages;

use App\Filament\Seller\Resources\OrderResource;
use Filament\Resources\Pages\Page;

class ViewOrder extends Page
{
    protected static string $resource = OrderResource::class;

    protected static string $view = 'filament.seller.resources.order-resource.pages.view-order';
}
