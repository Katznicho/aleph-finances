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
        Schema::create('revenues', function (Blueprint $table) {
            $table->id();
            $table->string('invoice_number')->unique();
            $table->string('client');
            $table->decimal('amount', 12, 2);
            $table->string('currency')->default('UGX');
            $table->date('invoice_date');
            $table->date('due_date')->nullable();
            $table->date('payment_date')->nullable();
            $table->string('status')->default('pending');
            $table->text('description')->nullable();
            $table->string('project')->nullable();
            $table->boolean('is_paid')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('revenues');
    }
};
