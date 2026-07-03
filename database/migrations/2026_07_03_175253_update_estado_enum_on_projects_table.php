<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement("ALTER TABLE projects DROP CONSTRAINT IF EXISTS projects_estado_check");

        DB::statement("
            UPDATE projects
            SET estado = 'activo'
            WHERE estado IN ('pendiente', 'en_proceso', 'en proceso', 'proceso')
        ");

        DB::statement("
            UPDATE projects
            SET estado = 'finalizado'
            WHERE estado IN ('completado', 'completada', 'finalizado', 'finalizada')
        ");

        DB::statement("
            UPDATE projects
            SET estado = 'pausado'
            WHERE estado IN ('cancelado', 'cancelada', 'pausado')
        ");

        DB::statement("
            ALTER TABLE projects
            ADD CONSTRAINT projects_estado_check
            CHECK (estado IN ('activo', 'pausado', 'finalizado'))
        ");
    }

    public function down(): void
    {
        DB::statement("ALTER TABLE projects DROP CONSTRAINT IF EXISTS projects_estado_check");

        DB::statement("
            UPDATE projects
            SET estado = 'en_proceso'
            WHERE estado IN ('activo', 'pausado')
        ");

        DB::statement("
            UPDATE projects
            SET estado = 'completado'
            WHERE estado = 'finalizado'
        ");

        DB::statement("
            ALTER TABLE projects
            ADD CONSTRAINT projects_estado_check
            CHECK (estado IN ('pendiente', 'en_proceso', 'completado'))
        ");
    }
};