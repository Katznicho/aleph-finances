<?php

namespace App\Filament\Superadmin\Resources\BranchResource\Pages;

use App\Filament\Superadmin\Resources\BranchResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Filament\Notifications\Notification;

class EditBranch extends EditRecord
{
    protected static string $resource = BranchResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function getSavedNotification(): ?Notification
    {
        return Notification::make()
            ->success()
            ->title('Branch updated')
            ->body('The branch has been updated successfully.');
    }
}
