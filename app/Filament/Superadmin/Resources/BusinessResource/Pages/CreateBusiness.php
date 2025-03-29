<?php

namespace App\Filament\Superadmin\Resources\BusinessResource\Pages;

use App\Filament\Superadmin\Resources\BusinessResource;
use Filament\Resources\Pages\CreateRecord;
use Filament\Notifications\Notification;

class CreateBusiness extends CreateRecord
{
    protected static string $resource = BusinessResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function getCreatedNotification(): ?Notification
    {
        return Notification::make()
            ->success()
            ->title('Business created')
            ->body('The business has been created successfully.');
    }
}
