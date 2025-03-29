<?php

namespace App\Filament\Widgets;

use App\Models\Revenue;
use App\Models\Requisition;
use App\Models\OpeningBalance;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class CashflowStatsOverview extends BaseWidget
{
    protected static ?int $sort = 1;
    protected function getStats(): array
    {
        $currentYear = date('Y');
        
        $totalCashIn = Revenue::whereYear('created_at', $currentYear)->sum('amount');
        $totalCashOut = Requisition::where('status', 'approved')->whereYear('created_at', $currentYear)->sum('amount');
        $openingBalance = OpeningBalance::where('year', $currentYear)->first()?->amount ?? 0;
        $currentBalance = $openingBalance + $totalCashIn - $totalCashOut;

        return [
            Stat::make('Total Cash In', number_format($totalCashIn, 2))
                ->description('Total revenue this year')
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->color('success'),
            
            Stat::make('Total Cash Out', number_format($totalCashOut, 2))
                ->description('Total expenses this year')
                ->descriptionIcon('heroicon-m-arrow-trending-down')
                ->color('danger'),
            
            Stat::make('Current Balance', number_format($currentBalance, 2))
                ->description('Available balance')
                ->descriptionIcon('heroicon-m-banknotes')
                ->color($currentBalance >= 0 ? 'success' : 'danger'),
        ];
    }
}