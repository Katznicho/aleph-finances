<?php

namespace App\Filament\Resources\CashOutTypeResource\Pages;

use App\Filament\Resources\CashOutTypeResource;
use Filament\Resources\Pages\CreateRecord;

class CreateCashOutType extends CreateRecord
{
    protected static string $resource = CashOutTypeResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function getCreatedNotificationTitle(): ?string
    {
        return 'Chart of Account created successfully';
    }
}