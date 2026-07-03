<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreProjectRequest;
use App\Http\Requests\UpdateProjectRequest;
use App\Models\Project;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Schema;
use Illuminate\View\View;

class ProjectController extends Controller
{
    public function index(): View
    {
        Gate::authorize('viewAny', Project::class);

        $user = auth()->user();

        $query = Project::query()->latest();

        if (! $user->hasRole('admin')) {
            $query->where(function ($q) use ($user) {
                if (Schema::hasColumn('projects', 'user_id')) {
                    $q->where('user_id', $user->id);
                }

                if (method_exists(Project::class, 'members')) {
                    $q->orWhereHas('members', function ($memberQuery) use ($user) {
                        $memberQuery->where('users.id', $user->id);
                    });
                }
            });
        }

        $projects = $query->paginate(10);

        return view('projects.index', compact('projects'));
    }

    public function create(): View
    {
    Gate::authorize('projects.create');

    return view('projects.create');
    }

    public function store(StoreProjectRequest $request): RedirectResponse
{
    Gate::authorize('projects.create');

    $data = $request->validated();

    $project = new Project();

    $project->nombre = $data['nombre'];
    $project->descripcion = $data['descripcion'] ?? null;
    $project->estado = $data['estado'];

    // Esta línea es obligatoria porque tu tabla projects tiene owner_id NOT NULL
    $project->owner_id = $request->user()->id;

    if (Schema::hasColumn('projects', 'user_id')) {
        $project->user_id = $request->user()->id;
    }

    $project->save();

    if (method_exists($project, 'members')) {
        $project->members()->syncWithoutDetaching([
            $request->user()->id,
        ]);
    }

    return redirect()
        ->route('projects.index')
        ->with('success', 'Proyecto creado correctamente.');
}

    public function show(Project $project): View
    {
        Gate::authorize('view', $project);

        if (method_exists(Project::class, 'tasks')) {
            $project->load('tasks');
        }

        if (method_exists(Project::class, 'members')) {
            $project->load('members');
        }

        return view('projects.show', compact('project'));
    }

    public function edit(Project $project): View
    {
        Gate::authorize('update', $project);

        return view('projects.edit', compact('project'));
    }

    public function update(UpdateProjectRequest $request, Project $project): RedirectResponse
    {
        Gate::authorize('update', $project);

        $data = $request->validated();

        if (Schema::hasColumn('projects', 'nombre')) {
            $project->nombre = $data['nombre'];
        }

        if (Schema::hasColumn('projects', 'descripcion')) {
            $project->descripcion = $data['descripcion'] ?? null;
        }

        if (Schema::hasColumn('projects', 'estado')) {
            $project->estado = $data['estado'];
        }

        $project->save();

        return redirect()
            ->route('projects.show', $project)
            ->with('success', 'Proyecto actualizado correctamente.');
    }

    public function destroy(Project $project): RedirectResponse
    {
        Gate::authorize('delete', $project);

        $project->delete();

        return redirect()
            ->route('projects.index')
            ->with('success', 'Proyecto eliminado correctamente.');
    }
}