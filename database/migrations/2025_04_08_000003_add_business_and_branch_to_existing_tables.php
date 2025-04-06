<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        $tables = [
            'users',
            'revenues',
            'requisitions',
            'opening_balances',
            'others',
            'loans',
            'intercompany_ins',
            'cash_out_types',
            // Add any other tables that need these fields
        ];

        foreach ($tables as $table) {
            Schema::table($table, function (Blueprint $table) {
                $table->foreignId('business_id')->nullable()->constrained()->cascadeOnDelete();
                $table->foreignId('branch_id')->nullable()->constrained()->cascadeOnDelete();
            });
        }
    }

    public function down(): void
    {
        $tables = [
            'users',
            'revenues',
            'requisitions',
            'opening_balances',
            'others',
            'loans',
            'intercompany_ins',
            'cash_out_types',
            // Add any other tables that need these fields
        ];

        foreach ($tables as $table) {
            Schema::table($table, function (Blueprint $table) {
                $table->dropForeignId('business_id');
                $table->dropForeignId('branch_id');
            });
        }
    }
};