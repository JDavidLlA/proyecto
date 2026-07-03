<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTaskRequest;
use App\Http\Requests\UpdateTaskRequest;
use App\Models\Project;
use App\Models\Task;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Schema;
use Illuminate\View\View;

class TaskController extends Controller
{
    public function index(Request $request, Project $project): View
    {
        Gate::authorize('viewAny', [Task::class, $project]);

        $buscar = trim((string) $request->input('buscar', ''));
        $estado = $request->input('estado');
        $prioridad = $request->input('prioridad');

        $estados = [
            'pendiente' => 'Pendiente',
            'en_progreso' => 'En progreso',
            'completada' => 'Completada',
        ];

        $prioridades = [
            'baja' => 'Baja',
            'media' => 'Media',
            'alta' => 'Alta',
            'urgente' => 'Urgente',
        ];

        $query = $project->tasks();

        if (method_exists(Task::class, 'assignee')) {
            $query->with('assignee');
        }

        if (method_exists(Task::class, 'completedBy')) {
            $query->with('completedBy');
        }

        if ($buscar !== '') {
            $query->where(function ($q) use ($buscar) {
                $q->where('titulo', 'ILIKE', '%' . $buscar . '%');

                if (Schema::hasColumn('tasks', 'descripcion')) {
                    $q->orWhere('descripcion', 'ILIKE', '%' . $buscar . '%');
                }
            });
        }

        if (! empty($estado) && array_key_exists($estado, $estados)) {
            $query->where('estado', $estado);
        }

        if (! empty($prioridad) && array_key_exists($prioridad, $prioridades)) {
            $query->where('prioridad', $prioridad);
        }

        if (Schema::hasColumn('tasks', 'prioridad')) {
            $query->orderByRaw("
                CASE prioridad
                    WHEN 'urgente' THEN 1
                    WHEN 'alta' THEN 2
                    WHEN 'media' THEN 3
                    WHEN 'baja' THEN 4
                    ELSE 5
                END
            ");
        }

        $query->latest();

        $tasks = $query
            ->paginate(10)
            ->withQueryString();

        return view('tasks.index', compact(
            'project',
            'tasks',
            'buscar',
            'estado',
            'prioridad',
            'estados',
            'prioridades'
        ));
    }

    public function create(Project $project): View
    {
        Gate::authorize('create', [Task::class, $project]);

        $project->load('members');

        $members = $project->members()
            ->orderBy('name')
            ->get();

        $estados = [
            'pendiente' => 'Pendiente',
            'en_progreso' => 'En progreso',
            'completada' => 'Completada',
        ];

        $prioridades = [
            'baja' => 'Baja',
            'media' => 'Media',
            'alta' => 'Alta',
            'urgente' => 'Urgente',
        ];

        return view('tasks.create', compact(
            'project',
            'members',
            'estados',
            'prioridades'
        ));
    }

    public function store(StoreTaskRequest $request, Project $project): RedirectResponse
    {
        Gate::authorize('create', [Task::class, $project]);

        $data = $request->validated();

        $data['project_id'] = $project->id;

        if (! Schema::hasColumn('tasks', 'assignee_id')) {
            unset($data['assignee_id']);
        }

        if (! Schema::hasColumn('tasks', 'prioridad')) {
            unset($data['prioridad']);
        }

        if (! Schema::hasColumn('tasks', 'due_date')) {
            unset($data['due_date']);
        }

        if (($data['estado'] ?? null) === 'completada') {
            if (Schema::hasColumn('tasks', 'completed_by')) {
                $data['completed_by'] = $request->user()->id;
            }

            if (Schema::hasColumn('tasks', 'completed_at')) {
                $data['completed_at'] = now();
            }
        }

        $task = Task::create($data);

        return redirect()
            ->route('projects.tasks.show', [$project, $task])
            ->with('success', 'Tarea creada correctamente.');
    }

    public function show(Project $project, Task $task): View
    {
        $this->ensureTaskBelongsToProject($project, $task);

        Gate::authorize('view', $task);

        $task->load([
            'project',
            'comments' => function ($query) {
                $query->latest();
            },
            'comments.user',
        ]);

        if (method_exists(Task::class, 'assignee')) {
            $task->load('assignee');
        }

        if (method_exists(Task::class, 'completedBy')) {
            $task->load('completedBy');
        }

        return view('tasks.show', compact('project', 'task'));
    }

    public function edit(Project $project, Task $task): View
    {
        $this->ensureTaskBelongsToProject($project, $task);

        Gate::authorize('update', $task);

        $project->load('members');

        $members = $project->members()
            ->orderBy('name')
            ->get();

        $estados = [
            'pendiente' => 'Pendiente',
            'en_progreso' => 'En progreso',
            'completada' => 'Completada',
        ];

        $prioridades = [
            'baja' => 'Baja',
            'media' => 'Media',
            'alta' => 'Alta',
            'urgente' => 'Urgente',
        ];

        return view('tasks.edit', compact(
            'project',
            'task',
            'members',
            'estados',
            'prioridades'
        ));
    }

    public function update(UpdateTaskRequest $request, Project $project, Task $task): RedirectResponse
    {
        $this->ensureTaskBelongsToProject($project, $task);

        Gate::authorize('update', $task);

        $data = $request->validated();

        if (! Schema::hasColumn('tasks', 'assignee_id')) {
            unset($data['assignee_id']);
        }

        if (! Schema::hasColumn('tasks', 'prioridad')) {
            unset($data['prioridad']);
        }

        if (! Schema::hasColumn('tasks', 'due_date')) {
            unset($data['due_date']);
        }

        if (($data['estado'] ?? null) === 'completada') {
            if (Schema::hasColumn('tasks', 'completed_by') && empty($task->completed_by)) {
                $data['completed_by'] = $request->user()->id;
            }

            if (Schema::hasColumn('tasks', 'completed_at') && empty($task->completed_at)) {
                $data['completed_at'] = now();
            }
        }

        if (($data['estado'] ?? null) !== 'completada') {
            if (Schema::hasColumn('tasks', 'completed_by')) {
                $data['completed_by'] = null;
            }

            if (Schema::hasColumn('tasks', 'completed_at')) {
                $data['completed_at'] = null;
            }
        }

        $task->update($data);

        return redirect()
            ->route('projects.tasks.show', [$project, $task])
            ->with('success', 'Tarea actualizada correctamente.');
    }

    public function complete(Request $request, Project $project, Task $task): RedirectResponse
    {
        $this->ensureTaskBelongsToProject($project, $task);

        Gate::authorize('complete', $task);

        $task->estado = 'completada';

        if (Schema::hasColumn('tasks', 'completed_by')) {
            $task->completed_by = $request->user()->id;
        }

        if (Schema::hasColumn('tasks', 'completed_at')) {
            $task->completed_at = now();
        }

        $task->save();

        return redirect()
            ->route('projects.tasks.show', [$project, $task])
            ->with('success', 'Tarea marcada como completada correctamente.');
    }

    public function destroy(Project $project, Task $task): RedirectResponse
    {
        $this->ensureTaskBelongsToProject($project, $task);

        Gate::authorize('delete', $task);

        $task->delete();

        return redirect()
            ->route('projects.tasks.index', $project)
            ->with('success', 'Tarea eliminada correctamente.');
    }

    private function ensureTaskBelongsToProject(Project $project, Task $task): void
    {
        abort_unless((int) $task->project_id === (int) $project->id, 404);
    }
}