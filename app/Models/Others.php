<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Others extends Model
{
    protected $fillable = [
        'name', 
        'description', 
        'amount', 
        'currency',
        'business_id',
        'branch_id'
    ];

    public function getAmountAttribute($value)
    {
        return number_format($value, 2);
    }

    public static function getCurrencySymbol($currency)
    {
        $symbols = [
            'UGX' => 'UGX',
            'USD' => '$',
            'EUR' => '€',
            'GBP' => '£',
            'JPY' => '¥',
            'AUD' => 'A$',
            'CAD' => 'C$',
        ];
    }

    public function business(): BelongsTo
    {
        return $this->belongsTo(Business::class);
    }

    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class);
    }
}
