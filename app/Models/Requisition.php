<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Requisition extends Model
{
    protected $fillable = [
        'reference_number',
        'cash_out_type_id',
        'amount',
        'currency',
        'description',
        'status',
        'business_id',
        'branch_id',
        'user_id',
        'requested_by',
        'reviewed_by',
        'approved_by',
        'status',
        'review_comments',
        'approval_comments',
        'requested_date',
        'review_date',
        'approval_date',
        'project_id',
        'is_paid',
        'payment_date',
        'budget_id', // Added budget_id
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'requested_date' => 'datetime',
        'review_date' => 'datetime',
        'approval_date' => 'datetime',
        'payment_date' => 'datetime',
        'is_paid' => 'boolean'
    ];

    public function cashOutType(): BelongsTo
    {
        return $this->belongsTo(CashOutType::class);
    }

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    public function requestedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'requested_by');
    }

    public function reviewedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }

    public function approvedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function budget(): BelongsTo
    {
        return $this->belongsTo(Budget::class);
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($requisition) {
            $requisition->reference_number = 'REQ-' . date('Y') . '-' . str_pad((static::count() + 1), 5, '0', STR_PAD_LEFT);
        });
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