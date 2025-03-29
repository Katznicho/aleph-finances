<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class InterCompanyIn extends Model
{
    protected $fillable = [
        'from_company',
        'amount',
        'date',
        'description',
        'status',
        'business_id',
        'branch_id'
    ];

    protected $casts = [
        'date' => 'date'
    ];

    public function business(): BelongsTo
    {
        return $this->belongsTo(Business::class);
    }

    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class);
    }
}
