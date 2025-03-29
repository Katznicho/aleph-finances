<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CapitalExpenditure extends Model
{
    protected $fillable = [
        'item_name',
        'amount',
        'currency',
        'purchase_date',
        'description',
        'reference_number',
        'supplier',
        'is_paid',
    ];

    protected $casts = [
        'purchase_date' => 'date',
        'is_paid' => 'boolean',
    ];
}
