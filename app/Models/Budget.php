<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Budget extends Model
{
    protected $fillable = [
        'name',
        'business_id',
        'branch_id',
        'start_date',
        'end_date',
        'status',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
    ];

    public function budgetItems(): HasMany
    {
        return $this->hasMany(BudgetItem::class);
    }

    public function getFormattedBudgetItemsAttribute()
    {
        return $this->budgetItems->map(function ($item) {
            return [
                'cash_out_type_id' => $item->cash_out_type_id,
                'amount' => $item->amount,
                'currency' => $item->currency,
                'description' => $item->description
            ];
        })->toArray();
    }
}
