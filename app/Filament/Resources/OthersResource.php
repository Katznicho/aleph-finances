<?php

namespace App\Filament\Resources;

use App\Filament\Resources\OthersResource\Pages;
use App\Models\Others;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;  // Add this import

class OthersResource extends Resource
{
    protected static ?string $model = Others::class;
    protected static ?string $navigationIcon = 'heroicon-o-plus-circle';
    protected static ?string $navigationGroup = 'Cash In';
    protected static ?string $navigationLabel = 'Other Income';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Hidden::make('business_id')
                    ->default(fn () => auth()->user()->business_id),
                Forms\Components\Hidden::make('branch_id')
                    ->default(fn () => auth()->user()->branch_id),

                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(255),
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
                Forms\Components\Textarea::make('description')
                    ->maxLength(65535)
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(fn (Builder $query) => $query
                ->where('business_id', auth()->user()->business_id)
                ->where('branch_id', auth()->user()->branch_id)
            )
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('amount')
                    ->money(fn ($record): string => $record->currency)
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable(),
            ])
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
            ])
            ->headerActions([
                Tables\Actions\ExportAction::make()
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListOthers::route('/'),
            'create' => Pages\CreateOthers::route('/create'),
            'edit' => Pages\EditOthers::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->where('business_id', auth()->user()->business_id)
            ->where('branch_id', auth()->user()->branch_id);
    }
}
