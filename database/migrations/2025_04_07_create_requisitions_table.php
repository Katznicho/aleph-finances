<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('requisitions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cash_out_type_id')->constrained()->cascadeOnDelete();
            $table->decimal('amount', 12, 2);
            $table->text('description');
            $table->foreignId('requested_by')->constrained('users');
            $table->foreignId('reviewed_by')->nullable()->constrained('users');
            $table->foreignId('approved_by')->nullable()->constrained('users');
            $table->enum('status', ['pending', 'reviewed', 'approved', 'rejected'])->default('pending');
            $table->text('review_comments')->nullable();
            $table->text('approval_comments')->nullable();
            $table->datetime('requested_date');
            $table->datetime('review_date')->nullable();
            $table->datetime('approval_date')->nullable();
            $table->string('reference_number')->unique();
            $table->string('currency')->default('UGX');
            $table->foreignId('budget_id')->nullable()->constrained();
            $table->boolean('is_paid')->default(false);
            $table->datetime('payment_date')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('requisitions');
    }
};