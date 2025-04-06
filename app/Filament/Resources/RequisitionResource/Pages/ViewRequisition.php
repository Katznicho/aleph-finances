<?php

namespace App\Filament\Resources\RequisitionResource\Pages;

use App\Filament\Resources\RequisitionResource;
use Filament\Resources\Pages\ViewRecord;
use Filament\Infolists;
use Filament\Infolists\Infolist;

class ViewRequisition extends ViewRecord
{
    protected static string $resource = RequisitionResource::class;

    public function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Infolists\Components\Section::make('Requisition Details')
                    ->schema([
                        Infolists\Components\TextEntry::make('reference_number')
                            ->label('Reference Number'),
                        Infolists\Components\TextEntry::make('budget.name')
                            ->label('Budget'),
                        Infolists\Components\TextEntry::make('amount')
                            ->money(fn ($record) => $record->currency),
                        Infolists\Components\TextEntry::make('description')
                            ->markdown(),
                        Infolists\Components\TextEntry::make('status')
                            ->badge()
                            ->color(fn (string $state): string => match ($state) {
                                'pending' => 'gray',
                                'reviewed' => 'warning',
                                'approved' => 'success',
                                'rejected' => 'danger',
                            }),
                    ])
                    ->columns(2),

                Infolists\Components\Section::make('Request Information')
                    ->schema([
                        Infolists\Components\TextEntry::make('requestedBy.name')
                            ->label('Requested By'),
                        Infolists\Components\TextEntry::make('requested_date')
                            ->dateTime(),
                        Infolists\Components\TextEntry::make('reviewedBy.name')
                            ->label('Reviewed By')
                            ->visible(fn ($record) => $record->reviewed_by !== null),
                        Infolists\Components\TextEntry::make('review_date')
                            ->dateTime()
                            ->visible(fn ($record) => $record->review_date !== null),
                        Infolists\Components\TextEntry::make('review_comments')
                            ->markdown()
                            ->visible(fn ($record) => $record->review_comments !== null),
                        Infolists\Components\TextEntry::make('approvedBy.name')
                            ->label('Approved By')
                            ->visible(fn ($record) => $record->approved_by !== null),
                        Infolists\Components\TextEntry::make('approval_date')
                            ->dateTime()
                            ->visible(fn ($record) => $record->approval_date !== null),
                        Infolists\Components\TextEntry::make('approval_comments')
                            ->markdown()
                            ->visible(fn ($record) => $record->approval_comments !== null),
                    ])
                    ->columns(2)
            ]);
    }
}