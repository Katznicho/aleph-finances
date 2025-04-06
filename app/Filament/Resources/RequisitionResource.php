<?php

namespace App\Filament\Resources;

use App\Filament\Resources\RequisitionResource\Pages;
use App\Models\Requisition;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

class RequisitionResource extends Resource
{
    protected static ?string $model = Requisition::class;
    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Hidden::make('business_id')
                    ->default(fn () => auth()->user()->business_id),
                Forms\Components\Hidden::make('branch_id')
                    ->default(fn () => auth()->user()->branch_id),
                Forms\Components\Hidden::make('requested_by')
                    ->default(fn () => auth()->id()),

                Forms\Components\Repeater::make('budgets')
                    ->schema([
                        Forms\Components\Select::make('budget_id')
                            ->relationship(
                                name: 'budget',
                                titleAttribute: 'name',
                                modifyQueryUsing: fn (Builder $query) => $query
                                    ->where('business_id', auth()->user()->business_id)
                                    ->where('branch_id', auth()->user()->branch_id)
                            )
                            ->required()
                            ->searchable()
                            ->preload()
                            ->live()
                            ->afterStateUpdated(function ($state, Forms\Set $set) {
                                if (!$state) {
                                    $set('requisition_items', []);
                                    return;
                                }
                                
                                $budget = \App\Models\Budget::with('budgetItems.cashOutType')->find($state);
                                if (!$budget) return;
                                
                                $items = $budget->budgetItems->map(function ($item) {
                                    return [
                                        'id' => $item->id,
                                        'cash_out_type_id' => $item->cash_out_type_id,
                                        'budget_item_name' => $item->cashOutType->name,
                                        'original_amount' => $item->amount,
                                        'amount' => 0,
                                        'currency' => $item->currency,
                                        'description' => $item->description
                                    ];
                                })->toArray();
                                
                                $set('requisition_items', []);
                                $set('selected_items', []);
                            }),

                        Forms\Components\Section::make('Budget Items')
                            ->schema([
                                Forms\Components\Select::make('requisition_items')
                                    ->multiple()
                                    ->searchable()
                                    ->preload()
                                    ->options(function (Forms\Get $get) {
                                        $budgetId = $get('budget_id');
                                        if (!$budgetId) return [];
                                        
                                        $budget = \App\Models\Budget::with('budgetItems.cashOutType')->find($budgetId);
                                        if (!$budget) return [];
                                        
                                        return $budget->budgetItems->mapWithKeys(function ($item) {
                                            return [
                                                $item->id => "{$item->cashOutType->name} ({$item->currency} " . 
                                                number_format($item->amount, 2) . ")"
                                            ];
                                        });
                                    })
                                    ->live()
                                    ->afterStateUpdated(function ($state, Forms\Set $set, Forms\Get $get) {
                                        if (!$state) {
                                            $set('selected_items', []);
                                            return;
                                        }
                                        
                                        $budgetId = $get('budget_id');
                                        $budget = \App\Models\Budget::with('budgetItems.cashOutType')->find($budgetId);
                                        if (!$budget) return;
                                        
                                        $selectedItems = $budget->budgetItems
                                            ->whereIn('id', $state)
                                            ->map(function ($item) {
                                                return [
                                                    'cash_out_type_id' => $item->cash_out_type_id,
                                                    'budget_item_name' => $item->cashOutType->name,
                                                    'original_amount' => $item->amount,
                                                    'amount' => 0,
                                                    'currency' => $item->currency,
                                                    'description' => $item->description
                                                ];
                                            })
                                            ->values()
                                            ->toArray();
                                        
                                        $set('selected_items', $selectedItems);
                                    })
                                    ->columnSpanFull(),
                                
                                Forms\Components\Repeater::make('selected_items')
                                    ->schema([
                                        Forms\Components\Grid::make()
                                            ->schema([
                                                Forms\Components\Hidden::make('cash_out_type_id'),
                                                Forms\Components\TextInput::make('budget_item_name')
                                                    ->disabled()
                                                    ->label('Budget Item'),
                                                Forms\Components\TextInput::make('original_amount')
                                                    ->disabled()
                                                    ->prefix(fn (Forms\Get $get) => $get('currency'))
                                                    ->label('Budgeted Amount'),
                                                Forms\Components\TextInput::make('amount')
                                                    ->label('Requested Amount')
                                                    ->prefix(fn (Forms\Get $get) => $get('currency'))
                                                    ->numeric()
                                                    ->required()
                                                    ->live()
                                                    ->rules([
                                                        'numeric',
                                                        'min:1',
                                                    ])
                                                    ->minValue(1)
                                                    ->default(0)
                                                    ->afterStateUpdated(function (Forms\Get $get, Forms\Set $set, $state) {
                                                        $originalAmount = $get('original_amount');
                                                        $set('amount_left', $originalAmount - ($state ?? 0));
                                                    }),
                                                
                                                Forms\Components\TextInput::make('amount_left')
                                                    ->disabled()
                                                    ->label('Amount Left')
                                                    ->prefix(fn (Forms\Get $get) => $get('currency'))
                                                    ->live()
                                                    ->afterStateHydrated(function ($component, $state, $record, Forms\Get $get) {
                                                        $originalAmount = $get('original_amount');
                                                        $requestedAmount = $get('amount') ?? 0;
                                                        $component->state($originalAmount - $requestedAmount);
                                                    }),  // Added missing comma here
                                                
                                                Forms\Components\Textarea::make('description')
                                                    ->rows(1)
                                            ])
                                            ->columns(5)
                                    ])
                                    ->disabled(fn ($get) => !$get('budget_id'))
                                    ->deletable(false)
                                    ->reorderable(false)
                                    ->columnSpanFull()
                            ])
                            ->columnSpanFull()
                    ])
                    ->addActionLabel('Add Another Budget')
                    ->cloneable()
                    ->itemLabel(fn (array $state): ?string => 
                        isset($state['budget_id']) ? 
                        \App\Models\Budget::find($state['budget_id'])?->name : 
                        'New Budget Request'
                    )
                    ->columnSpanFull()
            ])
            ->columns(12);
    }

    public static function table(Table $table): Table
    {
        return $table
        ->modifyQueryUsing(fn ($query) => $query
                ->where('business_id', auth()->user()->business_id)
                ->where('branch_id', auth()->user()->branch_id)
            )
            ->columns([
                Tables\Columns\TextColumn::make('reference_number')
                    ->searchable(),
                Tables\Columns\TextColumn::make('budget.name')
                    ->label('Budget')
                    ->searchable(),
                Tables\Columns\TextColumn::make('amount')
                    ->money(fn ($record) => $record->currency)
                    ->sortable(),
                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'pending' => 'gray',
                        'reviewed' => 'warning',
                        'approved' => 'success',
                        'rejected' => 'danger',
                    }),
                Tables\Columns\TextColumn::make('requested_date')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('requestedBy.name')
                    ->label('Requested By'),
                Tables\Columns\TextColumn::make('reviewedBy.name')
                    ->label('Reviewed By')
                    ->visible(fn ($record) => $record && $record->reviewed_by !== null),
                Tables\Columns\TextColumn::make('review_date')
                    ->dateTime()
                    ->visible(fn ($record) => $record && $record->review_date !== null),
                Tables\Columns\TextColumn::make('approvedBy.name')
                    ->label('Approved By')
                    ->visible(fn ($record) => $record && $record->approved_by !== null),
                Tables\Columns\TextColumn::make('approval_date')
                    ->dateTime()
                    ->visible(fn ($record) => $record && $record->approval_date !== null),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\Action::make('review')
                    ->visible(fn ($record) => $record->status === 'pending')
                    ->form([
                        Forms\Components\Select::make('status')
                            ->options([
                                'reviewed' => 'Mark as Reviewed',
                                'rejected' => 'Reject',
                            ])
                            ->required(),
                        Forms\Components\Textarea::make('review_comments')
                            ->required(),
                    ])
                    ->action(function (Requisition $record, array $data): void {
                        $record->update([
                            'status' => $data['status'],
                            'review_comments' => $data['review_comments'],
                            'reviewed_by' => auth()->id(),
                            'review_date' => now(),
                        ]);
                    }),
                Tables\Actions\Action::make('approve')
                    ->visible(fn ($record) => $record->status === 'reviewed')
                    ->form([
                        Forms\Components\Select::make('status')
                            ->options([
                                'approved' => 'Approve',
                                'rejected' => 'Reject',
                            ])
                            ->required(),
                        Forms\Components\Textarea::make('approval_comments')
                            ->required(),
                    ])
                    ->action(function (Requisition $record, array $data): void {
                        $record->update([
                            'status' => $data['status'],
                            'approval_comments' => $data['approval_comments'],
                            'approved_by' => auth()->id(),
                            'approval_date' => now(),
                        ]);
                    }),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListRequisitions::route('/'),
            'create' => Pages\CreateRequisition::route('/create'),
            'edit' => Pages\EditRequisition::route('/{record}/edit'),
            'view' => Pages\ViewRequisition::route('/{record}'),
        ];
    }
}