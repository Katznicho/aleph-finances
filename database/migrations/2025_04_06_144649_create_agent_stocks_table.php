<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('agent_stocks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('agent_id')->constrained();
            $table->foreignId('stock_id')->constrained();
            $table->decimal('quantity', 10, 2);
            $table->decimal('unit_price', 15, 2);
            $table->decimal('total_price', 15, 2);
            $table->enum('status', ['pending', 'distributed', 'completed', 'cancelled'])->default('pending');
            $table->timestamp('distribution_date');
            $table->text('notes')->nullable();
            $table->foreignId('business_id')->constrained();
            $table->foreignId('branch_id')->constrained();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('agent_stocks');
    }
};