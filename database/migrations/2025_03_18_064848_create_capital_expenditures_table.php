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
        Schema::create('capital_expenditures', function (Blueprint $table) {
            $table->id();
            $table->string('item_name');
            $table->decimal('amount', 12, 2);
            $table->string('currency')->default('UGX');
            $table->date('purchase_date');
            $table->text('description')->nullable();
            $table->string('reference_number')->nullable();
            $table->string('supplier')->nullable();
            $table->boolean('is_paid')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('capital_expenditures');
    }
};
