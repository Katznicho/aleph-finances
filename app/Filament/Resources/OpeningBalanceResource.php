<?php

namespace App\Filament\Resources;

use App\Filament\Resources\OpeningBalanceResource\Pages;
use App\Models\OpeningBalance;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class OpeningBalanceResource extends Resource
{
    protected static ?string $model = OpeningBalance::class;
    protected static ?string $navigationIcon = 'heroicon-o-banknotes';
    protected static ?string $navigationGroup = 'Cash In';
    protected static ?string $navigationLabel = 'Opening Balance';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
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
                Forms\Components\TextInput::make('year')
                    ->numeric()
                    ->required()
                    ->default(date('Y'))
                    ->unique(ignoreRecord: true)
                    ->minValue(2000)
                    ->maxValue(2099),
                Forms\Components\Textarea::make('description')
                    ->maxLength(65535)
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('year')
                    ->sortable(),
                Tables\Columns\TextColumn::make('amount')
                    ->money(fn ($record): string => $record->currency)
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('year', 'desc')
            ->filters([
                //
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
            'index' => Pages\ListOpeningBalances::route('/'),
            'create' => Pages\CreateOpeningBalance::route('/create'),
            'edit' => Pages\EditOpeningBalance::route('/{record}/edit'),
        ];
    }
}
