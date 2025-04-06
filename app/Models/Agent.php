<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Agent extends Model
{
    protected $fillable = [
        'name',
        'email',
        'phone',
        'address',
        'status',
        'business_id',
        'branch_id'
    ];

    public function stocks(): HasMany
    {
        return $this->hasMany(AgentStock::class);
    }
}
