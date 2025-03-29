<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('intercompany_ins', function (Blueprint $table) {
            $table->id();
            $table->string('company');
            $table->decimal('amount', 12, 2);
            $table->string('currency')->default('UGX');
            $table->date('transaction_date');
            $table->text('description')->nullable();
            $table->string('reference_number')->nullable();
            $table->boolean('is_received')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('intercompany_ins');
    }
};
