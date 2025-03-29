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
        Schema::create('payables', function (Blueprint $table) {
            $table->id();
            $table->string('vendor_name');
            $table->string('description');
            $table->string('reference_number')->nullable();
            $table->string('project')->nullable();
            $table->decimal('amount', 12, 2);
            $table->date('due_date');
            $table->date('payment_date')->nullable();
            $table->boolean('is_paid')->default(false);
            $table->string('payment_status')->default('pending');
            $table->string('assigned_to')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payables');
    }
};
