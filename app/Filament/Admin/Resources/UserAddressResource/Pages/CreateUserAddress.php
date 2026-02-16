<?php

namespace App\Filament\Admin\Resources\UserAddressResource\Pages;

use App\Filament\Admin\Resources\UserAddressResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateUserAddress extends CreateRecord
{
    protected static string $resource = UserAddressResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
