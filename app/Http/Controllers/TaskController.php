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

        $query = $project->tasks()
            ->orderByDesc('id');

        if ($buscar !== '') {
            $query->where(function ($q) use ($buscar) {
                $q->where('titulo', 'ILIKE', '%' . $buscar . '%');

                if (Schema::hasColumn('tasks', 'descripcion')) {
                    $q->orWhere('descripcion', 'ILIKE', '%' . $buscar . '%');
                }
            });
        }

        if (! empty($estado)) {
            $query->where('estado', $estado);
        }

        $tasks = $query
            ->paginate(10)
            ->withQueryString();

        return view('tasks.index', compact('project', 'tasks', 'buscar', 'estado'));
    }

    public function create(Project $project): View
    {
        Gate::authorize('create', [Task::class, $project]);

        return view('tasks.create', compact('project'));
    }

    public function store(StoreTaskRequest $request, Project $project): RedirectResponse
    {
        Gate::authorize('create', [Task::class, $project]);

        $data = $request->validated();
        $data['project_id'] = $project->id;

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
            'comments' => function ($query) {
                $query->latest();
            },
            'comments.user',
        ]);

        return view('tasks.show', compact('project', 'task'));
    }

    public function edit(Project $project, Task $task): View
    {
        $this->ensureTaskBelongsToProject($project, $task);

        Gate::authorize('update', $task);

        return view('tasks.edit', compact('project', 'task'));
    }

    public function update(UpdateTaskRequest $request, Project $project, Task $task): RedirectResponse
    {
        $this->ensureTaskBelongsToProject($project, $task);

        Gate::authorize('update', $task);

        $task->update($request->validated());

        return redirect()
            ->route('projects.tasks.show', [$project, $task])
            ->with('success', 'Tarea actualizada correctamente.');
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