<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class IntercompanyOut extends Model
{
    protected $fillable = [
        'company',
        'amount',
        'currency',
        'transaction_date',
        'description',
        'reference_number',
        'is_paid',
        'year',
    ];

    protected $casts = [
        'transaction_date' => 'date',
        'is_paid' => 'boolean',
    ];

    public static function getCompanies(): array
    {
        return [
            'swivel_tz' => 'Swivel TZ',
            'swivel_uganda' => 'Swivel Uganda',
            'swivel_kenya' => 'Swivel Kenya',
            'flexigrid' => 'Flexigrid',
            'nordic' => 'Nordic',
        ];
    }
}
