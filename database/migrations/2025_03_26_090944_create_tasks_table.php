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
        Schema::create('tasks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('category_id')->nullable()->constrained()->nullOnDelete();
            $table->string('title');
            $table->text('description')->nullable();
            $table->tinyInteger('priority')->default(2)->comment('1=nízká, 2=střední, 3=vysoká');
            $table->dateTime('due_date')->nullable();
            $table->enum('status', ['pending', 'completed', 'failed'])->default('pending');
            $table->integer('points_value')->default(5);
            $table->boolean('recurring')->default(false);
            $table->string('recurring_type')->nullable()->comment('daily, weekly, monthly');
            $table->integer('recurring_interval')->nullable()->comment('počet dnů, týdnů nebo měsíců');
            $table->boolean('is_recurring_parent')->default(false);
            $table->foreignId('parent_task_id')->nullable()->constrained('tasks')->nullOnDelete();
            $table->timestamps();

            // Indexy pro rychlejší vyhledávání
            $table->index(['user_id', 'status']);
            $table->index(['user_id', 'due_date']);
            $table->index(['user_id', 'priority']);
        });
    }

    /**
     * Reverse the migration.
     */
    public function down(): void
    {
        Schema::dropIfExists('tasks');
    }
};
