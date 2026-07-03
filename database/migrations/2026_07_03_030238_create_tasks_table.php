<?php

use App\Models\Project;
use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tasks', function (Blueprint $table) {
            $table->id();

            $table->foreignIdFor(Project::class)
                ->constrained()
                ->cascadeOnDelete();

            $table->foreignIdFor(User::class, 'assignee_id')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->string('titulo');
            $table->text('descripcion')->nullable();

            $table->enum('estado', ['pendiente', 'en_progreso', 'completada'])
                ->default('pendiente');

            $table->enum('prioridad', ['baja', 'media', 'alta'])
                ->default('media');

            $table->date('due_date')->nullable();

            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tasks');
    }
};