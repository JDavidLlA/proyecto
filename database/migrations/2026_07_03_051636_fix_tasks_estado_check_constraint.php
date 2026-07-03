<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('tasks')) {
            return;
        }

        DB::table('tasks')
            ->whereIn('estado', ['en progreso', 'en-progreso', 'en proceso'])
            ->update(['estado' => 'en_proceso']);

        DB::table('tasks')
            ->whereIn('estado', ['completado', 'completo', 'terminado'])
            ->update(['estado' => 'completada']);

        DB::statement('ALTER TABLE tasks DROP CONSTRAINT IF EXISTS tasks_estado_check');

        DB::statement("
            ALTER TABLE tasks
            ADD CONSTRAINT tasks_estado_check
            CHECK (estado IN ('pendiente', 'en_proceso', 'completada'))
        ");
    }

    public function down(): void
    {
        if (! Schema::hasTable('tasks')) {
            return;
        }

        DB::statement('ALTER TABLE tasks DROP CONSTRAINT IF EXISTS tasks_estado_check');

        DB::statement("
            ALTER TABLE tasks
            ADD CONSTRAINT tasks_estado_check
            CHECK (estado IN ('pendiente', 'en_proceso', 'completada'))
        ");
    }
};