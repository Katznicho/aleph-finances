<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BusinessDevelopment extends Model
{
    protected $fillable = [
        'category',
        'amount',
        'currency',
        'expense_date',
        'description',
        'reference_number',
        'is_paid',
    ];

    protected $casts = [
        'expense_date' => 'date',
        'is_paid' => 'boolean',
    ];

    public static function getCategories(): array
    {
        return [
            'client_entertainment' => 'Client Entertainment',
            'business_consultation' => 'Business Consultation',
            'financial_consultation' => 'Financial Consultation',
            'hr_consultation' => 'HR Consultation',
            'client_gift' => 'Client Gift',
            'client_seminars' => 'Client Seminars and Meeting',
            'research_development' => 'R&D',
            'brand_building' => 'Brand Building',
            'uniforms' => 'Uniforms',
            'branding_items' => 'Branding Items',
            'adverts' => 'Adverts',
            'interviews' => 'Interviews',
            'tender_costs' => 'Tender Costs',
        ];
    }
}
