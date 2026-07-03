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
            SET estado = CASE
                WHEN LOWER(TRIM(estado)) = 'pendiente' THEN 'pendiente'
                WHEN LOWER(TRIM(estado)) = 'en_proceso' THEN 'en_proceso'
                WHEN LOWER(TRIM(estado)) = 'en proceso' THEN 'en_proceso'
                WHEN LOWER(TRIM(estado)) = 'proceso' THEN 'en_proceso'
                WHEN LOWER(TRIM(estado)) = 'activo' THEN 'en_proceso'
                WHEN LOWER(TRIM(estado)) = 'completado' THEN 'completado'
                WHEN LOWER(TRIM(estado)) = 'completada' THEN 'completado'
                WHEN LOWER(TRIM(estado)) = 'finalizado' THEN 'completado'
                WHEN LOWER(TRIM(estado)) = 'terminado' THEN 'completado'
                ELSE 'pendiente'
            END
        ");

        DB::statement("
            ALTER TABLE projects
            ADD CONSTRAINT projects_estado_check
            CHECK (estado IN ('pendiente', 'en_proceso', 'completado'))
        ");
    }

    public function down(): void
    {
        DB::statement("ALTER TABLE projects DROP CONSTRAINT IF EXISTS projects_estado_check");
    }
};