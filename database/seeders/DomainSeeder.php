<?php

namespace Database\Seeders;

use App\Models\Comment;
use App\Models\Project;
use App\Models\Task;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DomainSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::firstOrCreate(
            ['email' => 'admin@gestor.com'],
            [
                'name' => 'Administrador',
                'password' => Hash::make('password'),
            ]
        );

        $lider = User::firstOrCreate(
            ['email' => 'lider@gestor.com'],
            [
                'name' => 'Líder de Proyecto',
                'password' => Hash::make('password'),
            ]
        );

        $colaborador = User::firstOrCreate(
            ['email' => 'colaborador@gestor.com'],
            [
                'name' => 'Colaborador',
                'password' => Hash::make('password'),
            ]
        );

        $invitado = User::firstOrCreate(
            ['email' => 'invitado@gestor.com'],
            [
                'name' => 'Invitado Cliente',
                'password' => Hash::make('password'),
            ]
        );

        $project = Project::updateOrCreate(
            ['nombre' => 'Sistema Web Gestor'],
            [
                'descripcion' => 'Proyecto colaborativo inicial para probar relaciones, tareas, miembros, prioridades y comentarios.',
                'estado' => 'activo',
                'prioridad' => 'alta',
                'owner_id' => $lider->id,
            ]
        );

        $project->members()->syncWithoutDetaching([
            $lider->id => ['project_role' => 'lider'],
            $colaborador->id => ['project_role' => 'colaborador'],
            $invitado->id => ['project_role' => 'invitado'],
        ]);

        $task1 = Task::updateOrCreate(
            [
                'project_id' => $project->id,
                'titulo' => 'Diseñar la base de datos',
            ],
            [
                'descripcion' => 'Crear migraciones, modelos y relaciones principales.',
                'estado' => 'completada',
                'prioridad' => 'alta',
                'assignee_id' => $lider->id,
                'due_date' => now()->addDays(3)->toDateString(),
            ]
        );

        $task2 = Task::updateOrCreate(
            [
                'project_id' => $project->id,
                'titulo' => 'Crear las vistas Blade',
            ],
            [
                'descripcion' => 'Preparar las pantallas principales del sistema.',
                'estado' => 'pendiente',
                'prioridad' => 'media',
                'assignee_id' => $colaborador->id,
                'due_date' => now()->addDays(7)->toDateString(),
            ]
        );

        $task3 = Task::updateOrCreate(
            [
                'project_id' => $project->id,
                'titulo' => 'Corregir módulo de prioridad',
            ],
            [
                'descripcion' => 'Verificar que proyectos y tareas muestren prioridad baja, media, alta y urgente.',
                'estado' => 'pendiente',
                'prioridad' => 'urgente',
                'assignee_id' => $lider->id,
                'due_date' => now()->addDay()->toDateString(),
            ]
        );

        Comment::firstOrCreate(
            [
                'task_id' => $task1->id,
                'user_id' => $lider->id,
                'cuerpo' => 'La base de datos inicial ya fue preparada.',
            ]
        );

        Comment::firstOrCreate(
            [
                'task_id' => $task2->id,
                'user_id' => $colaborador->id,
                'cuerpo' => 'Se iniciará el diseño de las vistas del proyecto.',
            ]
        );

        Comment::firstOrCreate(
            [
                'task_id' => $task2->id,
                'user_id' => $invitado->id,
                'cuerpo' => 'Quedo atento al avance del proyecto.',
            ]
        );

        Comment::firstOrCreate(
            [
                'task_id' => $task3->id,
                'user_id' => $lider->id,
                'cuerpo' => 'Esta tarea servirá para probar la prioridad urgente en el dashboard.',
            ]
        );
    }
}