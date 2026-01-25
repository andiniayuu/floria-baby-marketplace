<?php

namespace App\Filament\Admin\Resources\SellerRequestResource\Pages;

use App\Filament\Admin\Resources\SellerRequestResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditSellerRequest extends EditRecord
{
    protected static string $resource = SellerRequestResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }
}
