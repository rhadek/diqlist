<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migration.
     */
    public function up(): void
    {
        Schema::create('point_transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('task_id')->nullable()->constrained()->nullOnDelete();
            $table->integer('points_change');
            $table->enum('type', ['reward', 'penalty', 'bonus', 'adjustment'])->comment('reward=odměna, penalty=trest, bonus=extra body, adjustment=ruční úprava');
            $table->string('description')->nullable();
            $table->timestamps();

            // Index pro rychlejší vyhledávání podle uživatele a data
            $table->index(['user_id', 'created_at']);
        });
    }

    /**
     * Reverse the migration.
     */
    public function down(): void
    {
        Schema::dropIfExists('point_transactions');
    }
};
