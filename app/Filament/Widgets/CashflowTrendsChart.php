<?php

namespace App\Filament\Widgets;

use App\Models\Revenue;
use App\Models\Requisition;
use Filament\Widgets\ChartWidget;
use Carbon\Carbon;

class CashflowTrendsChart extends ChartWidget
{
    protected static ?string $heading = 'Monthly Cashflow Trends';
    protected static ?int $sort = 2;

    protected function getData(): array
    {
        $months = collect(range(1, 12))->map(function ($month) {
            return Carbon::create(date('Y'), $month, 1);
        });

        $cashIn = $months->map(function ($month) {
            return Revenue::whereYear('created_at', $month->year)
                ->whereMonth('created_at', $month->month)
                ->sum('amount');
        });

        $cashOut = $months->map(function ($month) {
            return Requisition::where('status', 'approved')
                ->whereYear('created_at', $month->year)
                ->whereMonth('created_at', $month->month)
                ->sum('amount');
        });

        return [
            'datasets' => [
                [
                    'label' => 'Cash In',
                    'data' => $cashIn,
                    'fill' => true,
                    'backgroundColor' => 'rgba(34, 197, 94, 0.1)',
                    'borderColor' => 'rgb(34, 197, 94)',
                ],
                [
                    'label' => 'Cash Out',
                    'data' => $cashOut,
                    'fill' => true,
                    'backgroundColor' => 'rgba(239, 68, 68, 0.1)',
                    'borderColor' => 'rgb(239, 68, 68)',
                ],
            ],
            'labels' => $months->map(fn ($date) => $date->format('M')),
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}