<?php

namespace App\Filament\Exports;

use App\Models\Receivable;
use Filament\Actions\Exports\ExportColumn;
use Filament\Actions\Exports\Exporter;
use Filament\Actions\Exports\Models\Export;

class ReceivableExporter extends Exporter
{
    protected static ?string $model = Receivable::class;

    public static function getColumns(): array
    {
        return [
            ExportColumn::make('invoice_number')
                ->label('Invoice Number'),
            ExportColumn::make('client'),
            ExportColumn::make('project_name')
                ->label('Project Name'),
            ExportColumn::make('lpo_number')
                ->label('LPO Number'),
            ExportColumn::make('date_of_invoicing')
                ->label('Invoice Date'),
            ExportColumn::make('due_date')
                ->label('Due Date'),
            ExportColumn::make('payment_date')
                ->label('Payment Date'),
            ExportColumn::make('ex_vat')
                ->label('Amount (Ex VAT)'),
            ExportColumn::make('vat_amount')
                ->label('VAT Amount'),
            ExportColumn::make('total')
                ->label('Total Amount'),
            ExportColumn::make('paid')
                ->label('Paid Amount'),
            ExportColumn::make('created_at')
                ->label('Created Date'),
        ];
    }

    public static function getCompletedNotificationBody(Export $export): string
    {
        $body = 'Your receivable export has completed and ' . number_format($export->successful_rows) . ' ' . str('row')->plural($export->successful_rows) . ' exported.';

        if ($failedRowsCount = $export->getFailedRowsCount()) {
            $body .= ' ' . number_format($failedRowsCount) . ' ' . str('row')->plural($failedRowsCount) . ' failed to export.';
        }

        return $body;
    }
}
