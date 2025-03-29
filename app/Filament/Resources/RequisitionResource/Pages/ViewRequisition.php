<?php

namespace App\Filament\Resources\RequisitionResource\Pages;

use App\Filament\Resources\RequisitionResource;
use Filament\Resources\Pages\ViewRecord;
use Filament\Infolists\Infolist;
use Filament\Infolists\Components;

class ViewRequisition extends ViewRecord
{
    protected static string $resource = RequisitionResource::class;

    public function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Components\Section::make()
                    ->schema([
                        Components\TextEntry::make('reference_number')
                            ->label('Reference Number'),
                        Components\TextEntry::make('cashOutType.name')
                            ->label('Chart of Accounts'),
                        Components\TextEntry::make('amount')
                            ->money(fn ($record) => $record->currency),
                        Components\TextEntry::make('description')
                            ->columnSpanFull(),
                        Components\TextEntry::make('project.name')
                            ->label('Project')
                            ->visible(fn ($record) => $record->project_id !== null),
                    ])
                    ->columns(3),
                
                Components\Section::make('Status Information')
                    ->schema([
                        Components\TextEntry::make('status')
                            ->badge()
                            ->color(fn (string $state): string => match ($state) {
                                'pending' => 'gray',
                                'reviewed' => 'warning',
                                'approved' => 'success',
                                'rejected' => 'danger',
                            }),
                        Components\TextEntry::make('requestedBy.name')
                            ->label('Requested By'),
                        Components\TextEntry::make('requested_date')
                            ->dateTime(),
                        Components\TextEntry::make('reviewedBy.name')
                            ->label('Reviewed By')
                            ->visible(fn ($record) => $record->reviewed_by !== null),
                        Components\TextEntry::make('review_date')
                            ->dateTime()
                            ->visible(fn ($record) => $record->review_date !== null),
                        Components\TextEntry::make('review_comments')
                            ->visible(fn ($record) => $record->review_comments !== null)
                            ->columnSpanFull(),
                        Components\TextEntry::make('approvedBy.name')
                            ->label('Approved By')
                            ->visible(fn ($record) => $record->approved_by !== null),
                        Components\TextEntry::make('approval_date')
                            ->dateTime()
                            ->visible(fn ($record) => $record->approval_date !== null),
                        Components\TextEntry::make('approval_comments')
                            ->visible(fn ($record) => $record->approval_comments !== null)
                            ->columnSpanFull(),
                    ])
                    ->columns(3),
            ]);
    }
}