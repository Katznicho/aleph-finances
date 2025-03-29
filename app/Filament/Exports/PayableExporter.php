<?php

namespace App\Filament\Exports;

use App\Models\Payable;
use Filament\Actions\Exports\ExportColumn;
use Filament\Actions\Exports\Exporter;
use Filament\Actions\Exports\Models\Export;

class PayableExporter extends Exporter
{
    protected static ?string $model = Payable::class;

    public static function getColumns(): array
    {
        return [
            ExportColumn::make('vendor_name')
                ->label('Vendor Name'),
            ExportColumn::make('description'),
            ExportColumn::make('reference_number')
                ->label('Reference Number'),
            ExportColumn::make('project'),
            ExportColumn::make('amount')
                ->label('Amount (UGX)'),
            ExportColumn::make('due_date')
                ->label('Due Date'),
            ExportColumn::make('payment_date')
                ->label('Payment Date'),
            ExportColumn::make('is_paid')
                ->label('Paid Status'),
            ExportColumn::make('payment_status')
                ->label('Payment Status'),
            ExportColumn::make('assigned_to')
                ->label('Assigned To'),
            ExportColumn::make('created_at')
                ->label('Created Date'),
        ];
    }

    public static function getCompletedNotificationBody(Export $export): string
    {
        $body = 'Your payable export has completed and ' . number_format($export->successful_rows) . ' ' . str('row')->plural($export->successful_rows) . ' exported.';

        if ($failedRowsCount = $export->getFailedRowsCount()) {
            $body .= ' ' . number_format($failedRowsCount) . ' ' . str('row')->plural($failedRowsCount) . ' failed to export.';
        }

        return $body;
    }
}
