<?php

namespace App\Filament\Resources;

use App\Filament\Resources\IntercompanyInResource\Pages;
use App\Models\IntercompanyIn;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class IntercompanyInResource extends Resource
{
    protected static ?string $model = IntercompanyIn::class;
    protected static ?string $navigationIcon = 'heroicon-o-building-office-2';
    protected static ?string $navigationGroup = 'Cash In';
    protected static ?string $navigationLabel = 'Intercompany In';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('company')
                    ->options(IntercompanyIn::getCompanies())
                    ->required(),
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
                Forms\Components\DatePicker::make('transaction_date')
                    ->required(),
                Forms\Components\TextInput::make('reference_number'),
                Forms\Components\Textarea::make('description')
                    ->maxLength(65535)
                    ->columnSpanFull(),
                Forms\Components\Toggle::make('is_received')
                    ->label('Received')
                    ->default(false),
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
                Tables\Columns\TextColumn::make('company')
                    ->searchable(),
                Tables\Columns\TextColumn::make('amount')
                    ->money(fn ($record): string => $record->currency)
                    ->sortable(),
                Tables\Columns\TextColumn::make('currency')
                    ->searchable(),
                Tables\Columns\TextColumn::make('transaction_date')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('reference_number')
                    ->searchable(),
                Tables\Columns\IconColumn::make('is_received')
                    ->boolean()
                    ->label('Received'),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('company')
                    ->options(IntercompanyIn::getCompanies()),
                Tables\Filters\Filter::make('transaction_date')
                    ->form([
                        Forms\Components\DatePicker::make('from'),
                        Forms\Components\DatePicker::make('until'),
                    ])
                    ->query(function ($query, array $data) {
                        return $query
                            ->when(
                                $data['from'],
                                fn($query, $date) => $query->whereDate('transaction_date', '>=', $date)
                            )
                            ->when(
                                $data['until'],
                                fn($query, $date) => $query->whereDate('transaction_date', '<=', $date)
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
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListIntercompanyIns::route('/'),
            'create' => Pages\CreateIntercompanyIn::route('/create'),
            'edit' => Pages\EditIntercompanyIn::route('/{record}/edit'),
        ];
    }
}
