<?php

namespace App\Filament\Admin\Resources\UserAddressResource\Pages;

use App\Filament\Admin\Resources\UserAddressResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditUserAddress extends EditRecord
{
    protected static string $resource = UserAddressResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
