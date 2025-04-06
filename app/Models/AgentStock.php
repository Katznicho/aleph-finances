<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AgentStock extends Model
{
    protected $fillable = [
        'agent_id',
        'stock_id',
        'quantity',
        'unit_price',
        'total_price',
        'status',
        'distribution_date',
        'business_id',
        'branch_id'
    ];

    protected $casts = [
        'distribution_date' => 'datetime'
    ];

    public function agent(): BelongsTo
    {
        return $this->belongsTo(Agent::class);
    }

    public function stock(): BelongsTo
    {
        return $this->belongsTo(Stock::class);
    }
}