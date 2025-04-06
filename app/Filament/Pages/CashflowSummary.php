<?php

namespace App\Filament\Pages;


use App\Models\CashOutType;
use App\Models\IntercompanyIn;
use App\Models\Loan;
use App\Models\OpeningBalance;
use App\Models\Others;
use App\Models\Requisition;
use App\Models\Revenue;
use Filament\Pages\Page;

class CashflowSummary extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-presentation-chart-line';
    protected static ?string $navigationLabel = 'Cashflow Summary';
    protected static ?string $title = 'Cashflow Summary';
    protected static ?int $navigationSort = -1;
    protected static ?string $slug = 'cashflow-summary';
    protected static string $view = 'filament.pages.cashflow-summary';

    public $startDate;
    public $endDate;
    public $selectedCategory = null;
    public $showBreakdownModal = false;
    public $breakdownData = [];

    public function mount()
    {
        $this->startDate = now()->subWeek()->format('Y-m-d');
        $this->endDate = now()->format('Y-m-d');
    }

    public function getViewData(): array
    {
        // Opening Balance
        $openingBalance = OpeningBalance::where('year', date('Y'))
        ->where('business_id', auth()->user()->business_id)
        ->where('branch_id', auth()->user()->branch_id)
        ->first();
        $openingBalanceAmount = $openingBalance ? $openingBalance->amount : 0;
    
        // Calculate Cash In with date range
        $otherIncome = Others::whereBetween('created_at', [$this->startDate, $this->endDate])
        ->where('business_id', auth()->user()->business_id)
        ->where('branch_id', auth()->user()->branch_id)
        ->sum('amount');
        $revenues = Revenue::whereBetween('created_at', [$this->startDate, $this->endDate])
        ->where('business_id', auth()->user()->business_id)
        ->where('branch_id', auth()->user()->branch_id)
        ->sum('amount');
        $intercompanyIn = IntercompanyIn::whereBetween('created_at', [$this->startDate, $this->endDate])
        ->where('business_id', auth()->user()->business_id)
        ->where('branch_id', auth()->user()->branch_id)
        ->sum('amount');
        $loanBorrowings = Loan::whereBetween('created_at', [$this->startDate, $this->endDate])
        ->where('business_id', auth()->user()->business_id)
        ->where('branch_id', auth()->user()->branch_id)
        ->sum('amount');
    
        // Get approved requisitions with date range
        $approvedRequisitions = Requisition::with(['budget.budgetItems.cashOutType'])
        ->where('business_id', auth()->user()->business_id)
        ->where('branch_id', auth()->user()->branch_id)
            ->whereBetween('requested_date', [$this->startDate, $this->endDate])
            ->where('status', 'approved')
            ->get();
    
        // Process cash out by categories
        $cashOutBreakdown = [
            'admin_cost' => [],
            'project_cost' => []
        ];
    
        foreach ($approvedRequisitions as $requisition) {
            foreach ($requisition->budget->budgetItems as $budgetItem) {
                $category = $budgetItem->cashOutType->category;
                $typeName = $budgetItem->cashOutType->name;
    
                if (!isset($cashOutBreakdown[$category][$typeName])) {
                    $cashOutBreakdown[$category][$typeName] = [
                        'total' => 0,
                        'items' => []
                    ];
                }
    
                $cashOutBreakdown[$category][$typeName]['total'] += $requisition->amount;
                $cashOutBreakdown[$category][$typeName]['items'][] = [
                    'reference' => $requisition->reference_number,
                    'amount' => $requisition->amount,
                    'currency' => $requisition->currency,
                    'date' => $requisition->requested_date->format('d M Y'),
                    'description' => $requisition->description,
                ];
            }
        }
    
        // Calculate totals
        $adminCosts = collect($cashOutBreakdown['admin_cost'])->sum('total');
        $projectCosts = collect($cashOutBreakdown['project_cost'])->sum('total');
        
        $totalCashIn = $openingBalanceAmount + $otherIncome + $revenues + $intercompanyIn + $loanBorrowings;
        $totalCashOut = $adminCosts + $projectCosts;
        $currentBalance = $totalCashIn - $totalCashOut;
    
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
