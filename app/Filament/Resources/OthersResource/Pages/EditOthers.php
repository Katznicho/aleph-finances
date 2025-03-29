<?php

namespace App\Filament\Resources\OthersResource\Pages;

use App\Filament\Resources\OthersResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditOthers extends EditRecord
{
    protected static string $resource = OthersResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
