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
        Schema::create('loans', function (Blueprint $table) {
            $table->id();
            $table->string('lender_name');
            $table->decimal('amount', 12, 2);
            $table->string('currency')->default('UGX');
            $table->date('loan_date');
            $table->date('repayment_date')->nullable();
            $table->string('status')->default('active');
            $table->text('description')->nullable();
            $table->string('reference_number')->nullable();
            $table->boolean('is_repaid')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('loans');
    }
};
