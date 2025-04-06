<?php

namespace App\Filament\Resources\CashOutTypeResource\Pages;

use App\Filament\Resources\CashOutTypeResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditCashOutType extends EditRecord
{
    protected static string $resource = CashOutTypeResource::class;

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

    protected function getSavedNotificationTitle(): ?string
    {
        return 'Chart of Account updated successfully';
    }
}