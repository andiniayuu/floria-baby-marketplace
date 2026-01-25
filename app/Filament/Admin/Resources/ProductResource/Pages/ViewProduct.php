<?php

namespace App\Filament\Admin\Resources\ProductResource\Pages;

use App\Filament\Admin\Resources\ProductResource;
use Filament\Resources\Pages\Page;

class ViewProduct extends Page
{
    protected static string $resource = ProductResource::class;

    protected static string $view = 'filament.admin.resources.product-resource.pages.view-product';
}
