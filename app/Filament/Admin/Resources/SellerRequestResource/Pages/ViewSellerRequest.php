<?php

namespace App\Filament\Admin\Resources\SellerRequestResource\Pages;

use App\Filament\Admin\Resources\SellerRequestResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewSellerRequest extends ViewRecord
{
    protected static string $resource = SellerRequestResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
