<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Statutory extends Model
{
    protected $fillable = [
        'type',
        'amount',
        'currency',
        'due_date',
        'payment_date',
        'status',
        'description',
        'reference_number',
        'is_paid',
        'year',
    ];

    protected $casts = [
        'due_date' => 'date',
        'payment_date' => 'date',
        'is_paid' => 'boolean',
    ];

    public static function getTypes(): array
    {
        return [
            'wht' => 'WHT',
            'vat' => 'VAT',
            'paye' => 'PAYE',
            'sdl' => 'SDL',
            'nssf' => 'NSSF',
            'nhif' => 'NHIF',
            'corporate_tax' => 'Corporate Tax',
        ];
    }

    public static function getStatuses(): array
    {
        return [
            'pending' => 'Pending',
            'processing' => 'Processing',
            'paid' => 'Paid',
            'overdue' => 'Overdue',
        ];
    }
}
