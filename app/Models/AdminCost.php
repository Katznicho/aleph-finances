<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AdminCost extends Model
{
    protected $fillable = [
        'category',
        'description',
        'amount',
        'currency',
        'expense_date',
        'type',
        'is_paid',
    ];

    public static function getCurrencies(): array
    {
        return [
            'UGX' => 'Ugandan Shilling',
            'USD' => 'US Dollar',
            'EUR' => 'Euro',
            'GBP' => 'British Pound',
            'KES' => 'Kenyan Shilling',
            'TZS' => 'Tanzanian Shilling',
            'RWF' => 'Rwandan Franc',
        ];
    }
}
