<?php

namespace App\Filament\Superadmin\Resources\RoleResource\Pages;

use App\Filament\Superadmin\Resources\RoleResource;
use Filament\Resources\Pages\CreateRecord;
use Filament\Notifications\Notification;

class CreateRole extends CreateRecord
{
    protected static string $resource = RoleResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function getCreatedNotification(): ?Notification
    {
        return Notification::make()
            ->success()
            ->title('Role created')
            ->body('The role has been created successfully.');
    }
}
