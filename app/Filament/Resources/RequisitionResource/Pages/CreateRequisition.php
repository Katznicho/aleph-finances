<?php

namespace App\Filament\Resources\RequisitionResource\Pages;

use App\Filament\Resources\RequisitionResource;
use App\Models\Requisition;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;

class CreateRequisition extends CreateRecord
{
    protected static string $resource = RequisitionResource::class;

    protected function handleRecordCreation(array $data): Model
    {
        $requisitions = collect($data['requisitions'])->map(function ($requisitionData) {
            return new Requisition([
                ...$requisitionData,
                'requested_by' => auth()->id(),
                'requested_date' => now(),
                'status' => 'pending',
                'reference_number' => 'REQ-' . date('Y') . '-' . str_pad((Requisition::count() + 1), 5, '0', STR_PAD_LEFT),
            ]);
        });

        $createdRequisitions = $requisitions->map(function ($requisition) {
            $requisition->save();
            return $requisition;
        });

        return $createdRequisitions->first();
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}