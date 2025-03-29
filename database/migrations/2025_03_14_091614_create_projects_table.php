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
        Schema::create('projects', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('client');
            $table->date('start_date');
            $table->date('end_date')->nullable();
            $table->decimal('budget', 12, 2)->default(0);
            $table->decimal('total_cost', 12, 2)->default(0);
            $table->text('description')->nullable();
            $table->enum('status', ['planned', 'in_progress', 'completed', 'on_hold'])->default('planned');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('projects');
    }
};
