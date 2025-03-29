<?php

namespace App\Filament\Resources\OthersResource\Pages;

use App\Filament\Resources\OthersResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListOthers extends ListRecords
{
    protected static string $resource = OthersResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
