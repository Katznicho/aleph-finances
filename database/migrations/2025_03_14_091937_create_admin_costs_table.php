<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('admin_costs', function (Blueprint $table) {
            $table->id();
            $table->string('category');
            $table->string('description')->nullable();
            $table->decimal('amount', 12, 2);
            $table->date('expense_date');
            $table->enum('type', [
                'salary',
                'staff_welfare',
                'utilities',
                'office_expenses',
                'medical',
                'allowances',
                'other'
            ]);
            $table->boolean('is_paid')->default(false);
            $table->string('currency')->default('UGX');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('admin_costs');
    }
};
