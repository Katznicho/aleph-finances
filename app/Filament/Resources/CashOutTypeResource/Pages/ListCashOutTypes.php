<?php

namespace App\Filament\Resources\CashOutTypeResource\Pages;

use App\Filament\Resources\CashOutTypeResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListCashOutTypes extends ListRecords
{
    protected static string $resource = CashOutTypeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}