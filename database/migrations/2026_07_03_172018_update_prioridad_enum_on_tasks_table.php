<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement("ALTER TABLE tasks DROP CONSTRAINT IF EXISTS tasks_prioridad_check");

        DB::statement("
            ALTER TABLE tasks
            ADD CONSTRAINT tasks_prioridad_check
            CHECK (prioridad IN ('baja', 'media', 'alta', 'urgente'))
        ");
    }

    public function down(): void
    {
        DB::statement("UPDATE tasks SET prioridad = 'alta' WHERE prioridad = 'urgente'");

        DB::statement("ALTER TABLE tasks DROP CONSTRAINT IF EXISTS tasks_prioridad_check");

        DB::statement("
            ALTER TABLE tasks
            ADD CONSTRAINT tasks_prioridad_check
            CHECK (prioridad IN ('baja', 'media', 'alta'))
        ");
    }
};