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
        $requisitions = collect($data['budgets'])->map(function ($budgetData) {
            $totalAmount = collect($budgetData['selected_items'])->sum('amount');
            
            if ($totalAmount <= 0) {
                throw new \Exception('Total amount must be greater than 0');
            }

            return Requisition::create([
                'business_id' => auth()->user()->business_id,
                'branch_id' => auth()->user()->branch_id,
                'budget_id' => $budgetData['budget_id'],
                'amount' => $totalAmount,
                'description' => collect($budgetData['selected_items'])
                    ->pluck('description')
                    ->filter()
                    ->join(', '),
                'requested_by' => auth()->id(),
                'requested_date' => now(),
                'status' => 'pending',
                'reference_number' => 'REQ-' . date('Y') . '-' . str_pad((Requisition::count() + 1), 5, '0', STR_PAD_LEFT),
            ]);
        });
    
        return $requisitions->first();
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}