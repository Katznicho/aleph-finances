<?php

namespace App\Filament\Exports;

use App\Models\Project;
use Filament\Actions\Exports\ExportColumn;
use Filament\Actions\Exports\Exporter;
use Filament\Actions\Exports\Models\Export;

class ProjectExporter extends Exporter
{
    protected static ?string $model = Project::class;

    public static function getColumns(): array
    {
        return [
            ExportColumn::make('name')
                ->label('Project Name'),
            ExportColumn::make('client'),
            ExportColumn::make('start_date')
                ->label('Start Date'),
            ExportColumn::make('end_date')
                ->label('End Date'),
            ExportColumn::make('budget')
                ->label('Budget (UGX)'),
            ExportColumn::make('total_cost')
                ->label('Total Cost (UGX)'),
            ExportColumn::make('description'),
            ExportColumn::make('status'),
            ExportColumn::make('created_at')
                ->label('Created Date'),
        ];
    }

    public static function getCompletedNotificationBody(Export $export): string
    {
        $body = 'Your project export has completed and ' . number_format($export->successful_rows) . ' ' . str('row')->plural($export->successful_rows) . ' exported.';

        if ($failedRowsCount = $export->getFailedRowsCount()) {
            $body .= ' ' . number_format($failedRowsCount) . ' ' . str('row')->plural($failedRowsCount) . ' failed to export.';
        }

        return $body;
    }
}
