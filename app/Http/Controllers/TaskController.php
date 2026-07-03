<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTaskRequest;
use App\Http\Requests\UpdateTaskRequest;
use App\Models\Project;
use App\Models\Task;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;

class TaskController extends Controller
{
    public function index(Project $project): View
    {
        Gate::authorize('viewAny', [Task::class, $project]);

        $tasks = $project->tasks()
            ->orderByDesc('id')
            ->paginate(10);

        return view('tasks.index', compact('project', 'tasks'));
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