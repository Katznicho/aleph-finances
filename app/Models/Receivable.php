<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Receivable extends Model
{
    protected $fillable = [
        'invoice_number',
        'client',
        'project_name',
        'lpo_number',
        'date_of_invoicing',
        'due_date',
        'payment_date',
        'ex_vat',
        'vat_amount',
        'total',
        'paid',
    ];
}
