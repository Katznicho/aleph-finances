<?php

namespace App\Filament\Widgets;

use App\Models\Receivable;
use App\Models\Payable;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;
use Carbon\CarbonPeriod;
use Carbon\Carbon;

class CashflowOverview extends ChartWidget
{
    protected static ?string $heading = 'Cash Flow Overview';
    protected int|string|array $columnSpan = 'full';
    protected static ?string $maxHeight = '300px';

    protected static ?int $sort = 3;

    protected function getData(): array
    {
        $period = CarbonPeriod::create(now()->subMonths(6), '1 month', now());
        
        $months = collect($period)->map(fn ($date) => $date->format('Y-m'));
        
        $receivables = $this->getMonthlyData(Receivable::class, 'total', $months);
        $payables = $this->getMonthlyData(Payable::class, 'amount', $months);
        
        $cashflow = $months->map(function ($month) use ($receivables, $payables) {
            return ($receivables[$month] ?? 0) - ($payables[$month] ?? 0);
        })->values();

        return [
            'datasets' => [
                [
                    'label' => 'Income',
                    'data' => $receivables->values(),
                    'backgroundColor' => '#36A2EB',
                    'borderColor' => '#36A2EB',
                    'type' => 'bar',
                ],
                [
                    'label' => 'Expenses',
                    'data' => $payables->values(),
                    'backgroundColor' => '#FF6384',
                    'borderColor' => '#FF6384',
                    'type' => 'bar',
                ],
                [
                    'label' => 'Net Cashflow',
                    'data' => $cashflow,
                    'backgroundColor' => '#4BC0C0',
                    'borderColor' => '#4BC0C0',
                    'type' => 'line',
                ],
            ],
            'labels' => $months->map(fn ($month) => Carbon::createFromFormat('Y-m', $month)->format('M Y')),
        ];
    }

    private function getMonthlyData($model, $field, $months)
    {
        // Use MySQL compatible date formatting
        $data = $model::select(
            DB::raw("DATE_FORMAT(created_at, '%Y-%m') as month"),
            DB::raw("SUM($field) as total")
        )
            ->whereDate('created_at', '>=', now()->subMonths(6))
            ->groupBy('month')
            ->pluck('total', 'month');
    
        return $months->mapWithKeys(function ($month) use ($data) {
            return [$month => $data[$month] ?? 0];
        });
    }

    protected function getType(): string
    {
        return 'bar';
    }
}