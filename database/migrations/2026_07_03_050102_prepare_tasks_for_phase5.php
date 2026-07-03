<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('tasks')) {
            Schema::create('tasks', function (Blueprint $table) {
                $table->id();
                $table->foreignId('project_id')->nullable()->constrained('projects')->nullOnDelete();
                $table->string('titulo')->nullable();
                $table->text('descripcion')->nullable();
                $table->string('estado')->default('pendiente');
                $table->date('fecha_limite')->nullable();
                $table->timestamps();
            });

            return;
        }

        Schema::table('tasks', function (Blueprint $table) {
            if (! Schema::hasColumn('tasks', 'project_id')) {
                $table->foreignId('project_id')->nullable()->constrained('projects')->nullOnDelete();
            }

            if (! Schema::hasColumn('tasks', 'titulo')) {
                $table->string('titulo')->nullable();
            }

            if (! Schema::hasColumn('tasks', 'descripcion')) {
                $table->text('descripcion')->nullable();
            }

            if (! Schema::hasColumn('tasks', 'estado')) {
                $table->string('estado')->default('pendiente');
            }

            if (! Schema::hasColumn('tasks', 'fecha_limite')) {
                $table->date('fecha_limite')->nullable();
            }
        });
    }

    public function down(): void
    {
        // Se deja vacío para evitar eliminar columnas que ya existían antes.
    }
};