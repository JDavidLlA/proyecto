<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('comments')) {
            Schema::create('comments', function (Blueprint $table) {
                $table->id();
                $table->foreignId('task_id')->nullable()->constrained('tasks')->nullOnDelete();
                $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
                $table->text('contenido')->nullable();
                $table->timestamps();
            });

            return;
        }

        Schema::table('comments', function (Blueprint $table) {
            if (! Schema::hasColumn('comments', 'task_id')) {
                $table->foreignId('task_id')->nullable()->constrained('tasks')->nullOnDelete();
            }

            if (! Schema::hasColumn('comments', 'user_id')) {
                $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            }

            if (! Schema::hasColumn('comments', 'contenido')) {
                $table->text('contenido')->nullable();
            }
        });
    }

    public function down(): void
    {
        // Se deja vacío para no borrar columnas que podrían venir de fases anteriores.
    }
};