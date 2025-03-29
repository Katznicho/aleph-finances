<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CentralCostShare extends Model
{
    protected $fillable = [
        'category',
        'person_responsible',
        'amount',
        'currency',
        'expense_date',
        'description',
        'reference_number',
        'is_paid',
        'year',
    ];

    protected $casts = [
        'expense_date' => 'date',
        'is_paid' => 'boolean',
    ];

    public static function getCategories(): array
    {
        return [
            'business_ops' => 'Business Ops & Performance Mgr',
            'zoho_subscription' => 'Zoho Monthly subscription',
            'george_murimi' => 'George Murimi',
            'captain_murimi' => 'Captain Murimi',
        ];
    }
}
