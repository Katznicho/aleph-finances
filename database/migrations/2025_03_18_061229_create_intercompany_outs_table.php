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
        Schema::create('intercompany_outs', function (Blueprint $table) {
            $table->id();
            $table->string('company');
            $table->decimal('amount', 12, 2);
            $table->string('currency')->default('UGX');
            $table->date('transaction_date');
            $table->text('description')->nullable();
            $table->string('reference_number')->nullable();
            $table->boolean('is_paid')->default(false);
            $table->string('year')->default('2024');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('intercompany_outs');
    }
};
