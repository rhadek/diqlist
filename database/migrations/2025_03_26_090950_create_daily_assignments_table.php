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
        Schema::create('daily_assignments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('task_id')->constrained()->onDelete('cascade');
            $table->date('date');
            $table->enum('status', ['pending', 'completed', 'failed'])->default('pending');
            $table->dateTime('completed_at')->nullable();
            $table->boolean('is_bonus')->default(false)->comment('Označení pro extra úkoly přidané pro vyrovnání trestných bodů');
            $table->timestamps();

            // Kombinovaný index pro rychlejší vyhledávání
            $table->index(['user_id', 'date', 'status']);

            // Zajištění unikátnosti - jeden úkol může být přiřazen na konkrétní den pouze jednou
            $table->unique(['task_id', 'date']);
        });
    }

    /**
     * Reverse the migration.
     */
    public function down(): void
    {
        Schema::dropIfExists('daily_assignments');
    }
};
