<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Loan extends Model
{
    protected $fillable = [
        'lender_name',
        'amount',
        'currency',
        'loan_date',
        'repayment_date',
        'status',
        'description',
        'reference_number',
        'is_repaid',
    ];

    protected $casts = [
        'loan_date' => 'date',
        'repayment_date' => 'date',
        'is_repaid' => 'boolean',
    ];

    public static function getStatuses(): array
    {
        return [
            'active' => 'Active',
            'pending' => 'Pending',
            'repaid' => 'Repaid',
            'overdue' => 'Overdue',
            'cancelled' => 'Cancelled',
        ];
    }
}
