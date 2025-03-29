<?php

namespace App\Filament\Pages;

use App\Models\AdminCost;
use App\Models\BusinessDevelopment;
use App\Models\CapitalExpenditure;
use App\Models\CashOutType;
use App\Models\CentralCostShare;
use App\Models\IntercompanyIn;
use App\Models\IntercompanyOut;
use App\Models\Loan;
use App\Models\LoanBorrowing;
use App\Models\LoanRepayment;
use App\Models\OpeningBalance;
use App\Models\Others;
use App\Models\Payable;
use App\Models\Project;
use App\Models\Receivable;
use App\Models\Requisition;
use App\Models\Revenue;
use App\Models\Statutory;
use Carbon\Carbon;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Pages\Page;

class CashflowSummary extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-presentation-chart-line';
    protected static ?string $navigationLabel = 'Cashflow Summary';
    protected static ?string $title = 'Cashflow Summary';
    protected static ?int $navigationSort = -1;
    protected static ?string $slug = 'cashflow-summary';
    protected static string $view = 'filament.pages.cashflow-summary';

    // Add these properties
    public $selectedCategory = null;
    public $showBreakdownModal = false;
    public $breakdownData = [];

    public function getViewData(): array
    {
        $currentYear = date('Y');

        // Opening Balance and Cash In calculations remain the same
        $openingBalance = OpeningBalance::where('year', $currentYear)->first();
        $openingBalanceAmount = $openingBalance ? $openingBalance->amount : 0;

        // Calculate Cash In
        $otherIncome = Others::whereYear('created_at', $currentYear)->sum('amount');
        $revenues = Revenue::whereYear('created_at', $currentYear)->sum('amount');
        $intercompanyIn = IntercompanyIn::whereYear('created_at', $currentYear)->sum('amount');
        $loanBorrowings = Loan::whereYear('created_at', $currentYear)->sum('amount');
        
        // Calculate Cash Out using Requisitions
        $cashOutQuery = Requisition::where('status', 'approved');
            // ->where('is_paid', true);

        $totalCashOut = $cashOutQuery->sum('amount');
        // dd($totalCashOut);

        $totalCashIn = $openingBalanceAmount + $otherIncome + $revenues + $intercompanyIn + $loanBorrowings;
        $currentBalance = $totalCashIn - $totalCashOut;

        // Get detailed breakdowns
        $cashOutBreakdown = [];
        
        // Get all cash out types first
        $cashOutTypes = CashOutType::where('is_active', true)
            ->with(['requisitions' => function ($query) {
                $query->where('status', 'approved');
                    // ->where('is_paid', true);
            }])
            ->get()
            ->groupBy('category');

        // Update the earlier cash out calculations as well
        $cashOutQuery = Requisition::whereYear('requested_date', $currentYear)  // Change here too
            ->where('status', 'approved');
            // ->where('is_paid', true);

        $adminCosts = (clone $cashOutQuery)
            ->whereHas('cashOutType', fn($q) => $q->where('category', 'admin_cost'))
            ->sum('amount');

        $projectCosts = (clone $cashOutQuery)
            ->whereHas('cashOutType', fn($q) => $q->where('category', 'project_cost'))
            ->sum('amount');

        $totalCashIn = $openingBalanceAmount + $otherIncome + $revenues + $intercompanyIn + $loanBorrowings;
        // $totalCashOut = $adminCosts + $projectCosts;
        $currentBalance = $totalCashIn - $totalCashOut;

        // Get detailed breakdowns
        $cashOutBreakdown = [];
        
        // Get all cash out types first
        $cashOutTypes = CashOutType::where('is_active', true)
            ->with(['requisitions' => function ($query) use ($currentYear) {
                $query->whereYear('created_at', $currentYear)
                    ->where('status', 'approved');
                    // ->where('is_paid', true);
            }])
            ->get()
            ->groupBy('category');

        // Process Administrative Costs
        $adminCosts = isset($cashOutTypes['admin_cost']) 
            ? $cashOutTypes['admin_cost']->pluck('requisitions')->flatten()->sum('amount')
            : 0;

        // Process Project Costs
        $projectCosts = isset($cashOutTypes['project_cost']) 
            ? $cashOutTypes['project_cost']->pluck('requisitions')->flatten()->sum('amount')
            : 0;

        $totalCashIn = $openingBalanceAmount + $otherIncome + $revenues + $intercompanyIn + $loanBorrowings;
        $totalCashOut = $adminCosts + $projectCosts;
        $currentBalance = $totalCashIn - $totalCashOut;

        // Prepare detailed breakdown
        $cashOutBreakdown = [
            'admin_cost' => isset($cashOutTypes['admin_cost']) 
                ? $cashOutTypes['admin_cost']->mapWithKeys(function ($type) {
                    return [$type->name => [
                        'total' => $type->requisitions->sum('amount'),
                        'items' => $type->requisitions->map(function ($req) {
                            return [
                                'reference' => $req->reference_number,
                                'amount' => $req->amount,
                                'currency' => $req->currency,
                                'date' => $req->requested_date->format('d M Y'),
                                'description' => $req->description,
                            ];
                        })->toArray(),
                    ]];
                })
                : collect(),
            'project_cost' => isset($cashOutTypes['project_cost'])
                ? $cashOutTypes['project_cost']->mapWithKeys(function ($type) {
                    return [$type->name => [
                        'total' => $type->requisitions->sum('amount'),
                        'items' => $type->requisitions->map(function ($req) {
                            return [
                                'reference' => $req->reference_number,
                                'amount' => $req->amount,
                                'currency' => $req->currency,
                                'date' => $req->requested_date->format('d M Y'),
                                'description' => $req->description,
                            ];
                        })->toArray(),
                    ]];
                })
                : collect(),
        ];

        return [
            'openingBalance' => number_format($openingBalanceAmount, 2),
            'totalCashIn' => number_format($totalCashIn, 2),
            'totalCashOut' => number_format($totalCashOut, 2),
            'currentBalance' => number_format($currentBalance, 2),
            'rawCurrentBalance' => $currentBalance,
            'cashInBreakdown' => [
                'Opening Balance' => $openingBalanceAmount,
                'Revenues' => $revenues,
                'Intercompany In' => $intercompanyIn,
                'Loans & Borrowings' => $loanBorrowings,
                'Other Income' => $otherIncome,
            ],
            'cashOutBreakdown' => $cashOutBreakdown,
        ];
    }
}
