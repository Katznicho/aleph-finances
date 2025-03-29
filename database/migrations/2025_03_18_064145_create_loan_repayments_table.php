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
        Schema::create('loan_repayments', function (Blueprint $table) {
            $table->id();
            $table->string('lender');
            $table->decimal('amount', 12, 2);
            $table->string('currency')->default('UGX');
            $table->date('due_date');
            $table->date('payment_date')->nullable();
            $table->string('status')->default('pending');
            $table->text('description')->nullable();
            $table->string('reference_number')->nullable();
            $table->boolean('is_paid')->default(false);
            $table->string('year')->default('2025');
            $table->string('type')->default('repayment'); // repayment or arrears
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('loan_repayments');
    }
};
