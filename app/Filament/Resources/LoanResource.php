<?php

namespace App\Filament\Resources;

use App\Filament\Resources\LoanResource\Pages;
use App\Models\Loan;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class LoanResource extends Resource
{
    protected static ?string $model = Loan::class;
    protected static ?string $navigationIcon = 'heroicon-o-currency-dollar';
    protected static ?string $navigationGroup = 'Cash In';
    protected static ?string $navigationLabel = 'Loans & Borrowings';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('lender_name')
                    ->required()
                    ->label('Lender Name'),
                Forms\Components\TextInput::make('amount')
                    ->numeric()
                    ->required(),
                Forms\Components\Select::make('currency')
                    ->options([
                        'UGX' => 'Ugandan Shilling',
                        'USD' => 'US Dollar',
                        'EUR' => 'Euro',
                        'GBP' => 'British Pound',
                        'KES' => 'Kenyan Shilling',
                        'TZS' => 'Tanzanian Shilling',
                    ])
                    ->default('UGX')
                    ->required(),
                Forms\Components\DatePicker::make('loan_date')
                    ->required(),
                Forms\Components\DatePicker::make('repayment_date'),
                Forms\Components\Select::make('status')
                    ->options(Loan::getStatuses())
                    ->default('active')
                    ->required(),
                Forms\Components\TextInput::make('reference_number'),
                Forms\Components\Toggle::make('is_repaid')
                    ->label('Repaid')
                    ->default(false),
                Forms\Components\Textarea::make('description')
                    ->maxLength(65535)
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
        ->modifyQueryUsing(fn ($query) => $query
                ->where('business_id', auth()->user()->business_id)
                ->where('branch_id', auth()->user()->branch_id)
            )
            ->columns([
                Tables\Columns\TextColumn::make('lender_name')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('amount')
                    ->money(fn ($record): string => $record->currency)
                    ->sortable(),
                Tables\Columns\TextColumn::make('loan_date')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('repayment_date')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'active' => 'warning',
                        'pending' => 'info',
                        'repaid' => 'success',
                        'overdue' => 'danger',
                        'cancelled' => 'gray',
                    }),
                Tables\Columns\IconColumn::make('is_repaid')
                    ->boolean()
                    ->label('Repaid'),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options(Loan::getStatuses()),
                Tables\Filters\Filter::make('loan_date')
                    ->form([
                        Forms\Components\DatePicker::make('from'),
                        Forms\Components\DatePicker::make('until'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['from'],
                                fn (Builder $query, $date): Builder => $query->whereDate('loan_date', '>=', $date),
                            )
                            ->when(
                                $data['until'],
                                fn (Builder $query, $date): Builder => $query->whereDate('loan_date', '<=', $date),
                            );
                    }),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->headerActions([
                Tables\Actions\ExportAction::make()
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListLoans::route('/'),
            'create' => Pages\CreateLoan::route('/create'),
            'edit' => Pages\EditLoan::route('/{record}/edit'),
        ];
    }
}
