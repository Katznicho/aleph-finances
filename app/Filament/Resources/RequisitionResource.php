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
                Forms\Components\Repeater::make('requisitions')
                    ->schema([
                        Forms\Components\Grid::make()
                            ->schema([
                                Forms\Components\Select::make('cash_out_type_id')
                                    ->relationship('cashOutType', 'name')
                                    ->required()
                                    ->searchable()
                                    ->preload()
                                    ->label('Chart of Accounts')
                                    ->createOptionForm([
                                        Forms\Components\TextInput::make('name')
                                            ->required()
                                            ->label('Account Name'),
                                        Forms\Components\Select::make('category')
                                            ->required()
                                            ->options([
                                                'admin_cost' => 'Administrative Cost',
                                                'project_cost' => 'Project Cost',
                                            ]),
                                    ])
                                    ->columnSpan(3),
                                Forms\Components\TextInput::make('amount')
                                    ->required()
                                    ->numeric()
                                    ->columnSpan(2),
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
                                Forms\Components\Select::make('project_id')
                                    ->relationship('project', 'name')
                                    ->searchable()
                                    ->preload()
                                    ->visible(fn (Forms\Get $get) => 
                                        $get('cash_out_type_id') && 
                                        Requisition::find($get('cash_out_type_id'))?->category === 'project_cost'
                                    )
                                    ->columnSpan(3),
                                Forms\Components\Textarea::make('description')
                                    ->required()
                                    ->rows(1)
                                    ->columnSpan(3),
                            ])
                            ->columns(6)
                    ])
                    ->defaultItems(1)
                    ->addActionLabel('Add Another Requisition')
                    ->cloneable()
                    ->itemLabel(fn (array $state): ?string => 
                        ($state['amount'] ?? '') . ' ' . ($state['currency'] ?? '') . ' - ' . 
                        (isset($state['description']) ? substr($state['description'], 0, 20) . '...' : 'New Requisition')
                    )
                    ->collapsible()
                    ->collapsed()
                    ->columnSpanFull()
                    ->reorderableWithButtons()
                    ->deleteAction(
                        fn (Forms\Components\Actions\Action $action) => $action->requiresConfirmation()
                    )
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('reference_number')
                    ->searchable(),
                Tables\Columns\TextColumn::make('cashOutType.name')
                    ->label('Chart of Accounts')
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