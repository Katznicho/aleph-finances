<?php

namespace App\Filament\Resources\IntercompanyInResource\Pages;

use App\Filament\Resources\IntercompanyInResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListIntercompanyIns extends ListRecords
{
    protected static string $resource = IntercompanyInResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
