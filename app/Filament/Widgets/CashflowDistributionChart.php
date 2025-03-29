<?php

namespace App\Filament\Widgets;

use App\Models\Requisition;
use App\Models\CashOutType;
use Filament\Widgets\ChartWidget;
use Carbon\Carbon;

class CashflowDistributionChart extends ChartWidget
{
    protected static ?string $heading = 'Cash Out Distribution';
    protected static ?int $sort = 2;  // Same sort as trends to keep them in the same row
    protected int | string | array $columnSpan = 'half';  // Half width to sit beside trends

    protected function getData(): array
    {
        $currentYear = date('Y');

        $cashOutTypes = CashOutType::where('is_active', true)
            ->with(['requisitions' => function ($query) use ($currentYear) {
                $query->where('status', 'approved')
                    ->whereYear('created_at', $currentYear);
            }])
            ->get();

        $data = $cashOutTypes->map(function ($type) {
            return $type->requisitions->sum('amount');
        });

        $labels = $cashOutTypes->pluck('name');

        $colors = [
            'rgb(59, 130, 246)', // Blue
            'rgb(16, 185, 129)', // Green
            'rgb(249, 115, 22)', // Orange
            'rgb(236, 72, 153)', // Pink
            'rgb(139, 92, 246)', // Purple
            'rgb(234, 179, 8)',  // Yellow
        ];

        return [
            'datasets' => [
                [
                    'data' => $data,
                    'backgroundColor' => $colors,
                    'borderWidth' => 0,
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'pie';
    }

    protected function getOptions(): array
    {
        return [
            'plugins' => [
                'legend' => [
                    'position' => 'bottom',
                ],
                'tooltip' => [
                    'callbacks' => [
                        'label' => "function(context) {
                            return context.label + ': UGX ' + 
                                new Intl.NumberFormat().format(context.raw)
                        }",
                    ],
                ],
            ],
        ];
    }
}