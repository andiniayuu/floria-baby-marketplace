<?php

namespace App\Filament\Seller\Resources\ProductResource\Pages;

use App\Filament\Seller\Resources\ProductResource;
use Filament\Resources\Pages\Page;

class ViewProduct extends Page
{
    protected static string $resource = ProductResource::class;

    protected static string $view = 'filament.seller.resources.product-resource.pages.view-product';
}
