<?php

namespace App\Filament\Seller\Resources\SalesReportResource\Pages;

use App\Filament\Seller\Resources\SalesReportResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditSalesReport extends EditRecord
{
    protected static string $resource = SalesReportResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
