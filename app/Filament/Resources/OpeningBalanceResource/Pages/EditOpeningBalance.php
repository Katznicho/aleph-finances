<?php

namespace App\Filament\Resources\OpeningBalanceResource\Pages;

use App\Filament\Resources\OpeningBalanceResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditOpeningBalance extends EditRecord
{
    protected static string $resource = OpeningBalanceResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
