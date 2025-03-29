<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CashOutType extends Model
{
    protected $fillable = [
        'name',
        'category',
        'description',
        'is_active',
        'business_id',
        'branch_id',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function requisitions(): HasMany
    {
        return $this->hasMany(Requisition::class);
    }

    public function business()
    {
        return $this->belongsTo(Business::class);
    }

    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }
}