<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payable extends Model
{
    protected $fillable = [
        'vendor_name',
        'description',
        'reference_number',
        'project',
        'amount',
        'due_date',
        'payment_date',
        'is_paid',
        'payment_status',
        'assigned_to',
    ];
}
