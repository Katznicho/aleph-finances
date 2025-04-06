<?php

namespace App\Filament\Resources;

use App\Models\Budget;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class BudgetResource extends Resource
{
    protected static ?string $model = Budget::class;
    protected static ?string $navigationIcon = 'heroicon-o-calculator';
    protected static ?string $navigationGroup = 'Project Management';
    protected static ?int $navigationSort = -1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Hidden::make('business_id')
                    ->default(fn () => auth()->user()->business_id),
                Forms\Components\Hidden::make('branch_id')
                    ->default(fn () => auth()->user()->branch_id),

                Forms\Components\Section::make('Budget Details')
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->required()
                            ->maxLength(255)
                            ->label('Project Name'),
                        Forms\Components\DatePicker::make('start_date')
                            ->required(),
                        Forms\Components\DatePicker::make('end_date')
                            ->required()
                            ->after('start_date'),
                        Forms\Components\Select::make('status')
                            ->options([
                                'draft' => 'Draft',
                                'active' => 'Active',
                                'closed' => 'Closed',
                            ])
                            ->default('draft')
                            ->required(),
                    ])->columns(2),

                Forms\Components\Section::make('Budget Items')
                    ->schema([
                        Forms\Components\Repeater::make('budgetItems')
                            ->relationship()
                            ->schema([
                                Forms\Components\Select::make('cash_out_type_id')
                                    ->relationship(
                                        name: 'cashOutType',
                                        titleAttribute: 'name',
                                        modifyQueryUsing: fn (Builder $query) => $query
                                            ->where('business_id', auth()->user()->business_id)
                                            ->where('branch_id', auth()->user()->branch_id)
                                    )
                                    ->required()
                                    ->searchable()
                                    ->preload()
                                    ->columnSpan(2),
                                Forms\Components\TextInput::make('amount')
                                    ->required()
                                    ->numeric()
                                    ->columnSpan(1),
                                Forms\Components\Select::make('currency')
                                    ->options([
                                        'UGX' => 'UGX',
                                        'USD' => 'USD',
                                        'EUR' => 'EUR',
                                        'GBP' => 'GBP',
                                        'KES' => 'KShs',
                                        'TZS' => 'TZS',
                                    ])
                                    ->default('UGX')
                                    ->required()
                                    ->columnSpan(1),
                                Forms\Components\Textarea::make('description')
                                    ->rows(1)
                                    ->columnSpan(2),
                            ])
                            ->columns(6)
                            ->defaultItems(1)
                            ->reorderableWithButtons()
                            ->collapsible()
                            ->itemLabel(fn (array $state): ?string => 
                                $state['amount'] ? number_format($state['amount']) . ' ' . ($state['currency'] ?? '') : 'New Budget Item'
                            ),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Project Name')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('start_date')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('end_date')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->sortable(),
                Tables\Columns\TextColumn::make('total_budget')
                    ->label('Total Budget')
                    ->money('UGX')
                    ->getStateUsing(function ($record) {
                        return $record->budgetItems->sum('amount');
                    }),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'draft' => 'Draft',
                        'active' => 'Active',
                        'closed' => 'Closed',
                    ]),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => \App\Filament\Resources\BudgetResource\Pages\ListBudgets::route('/'),
            'create' => \App\Filament\Resources\BudgetResource\Pages\CreateBudget::route('/create'),
            'edit' => \App\Filament\Resources\BudgetResource\Pages\EditBudget::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->where('business_id', auth()->user()->business_id)
            ->where('branch_id', auth()->user()->branch_id);
    }
}
