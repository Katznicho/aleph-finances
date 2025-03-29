<?php

namespace App\Filament\Exports;

use App\Models\AdminCost;
use Filament\Actions\Exports\ExportColumn;
use Filament\Actions\Exports\Exporter;
use Filament\Actions\Exports\Models\Export;

class AdminCostExporter extends Exporter
{
    protected static ?string $model = AdminCost::class;

    public static function getColumns(): array
    {
        return [
            ExportColumn::make('category'),
            ExportColumn::make('description'),
            ExportColumn::make('amount')
                ->label('Amount'),
            ExportColumn::make('currency'),
            ExportColumn::make('expense_date')
                ->label('Expense Date'),
            ExportColumn::make('type'),
            ExportColumn::make('is_paid')
                ->label('Paid Status'),
            ExportColumn::make('created_at')
                ->label('Created Date'),
        ];
    }

    public function getFileName(Export $export): string
    {
        return 'admin-costs-' . $export->created_at->format('Y-m-d') . '.' . $export->format;
    }

    protected function setUp(): void
    {
        $this->withChunkSize(100)
            ->withWriterType('csv');
    }

    public static function getCompletedNotificationBody(Export $export): string
    {
        $body = 'Your admin cost export has completed and ' . number_format($export->successful_rows) . ' ' . str('row')->plural($export->successful_rows) . ' exported.';

        if ($failedRowsCount = $export->getFailedRowsCount()) {
            $body .= ' ' . number_format($failedRowsCount) . ' ' . str('row')->plural($failedRowsCount) . ' failed to export.';
        }

        return $body;
    }
}
