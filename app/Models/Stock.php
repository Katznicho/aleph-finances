<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Stock extends Model
{
    protected $fillable = [
        'name',
        'quantity',
        'unit',
        'unit_price',
        'total_price',
        'project_id',
        'business_id',
        'branch_id'
    ];

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    public function agentStocks(): HasMany
    {
        return $this->hasMany(AgentStock::class);
    }
}
