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
    Schema::create('stocks', function (Blueprint $table) {
        $table->id();
        $table->string('name');
        $table->decimal('quantity', 10, 2);
        $table->string('unit');
        $table->decimal('unit_price', 15, 2);
        $table->decimal('total_price', 15, 2);
        $table->foreignId('project_id')->constrained();
        $table->foreignId('business_id')->constrained();
        $table->foreignId('branch_id')->constrained();
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stocks');
    }
};
