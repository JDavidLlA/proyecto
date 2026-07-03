<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreProjectRequest;
use App\Http\Requests\UpdateProjectRequest;
use App\Models\Project;
use App\Models\Task;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Schema;
use Illuminate\View\View;

class ProjectController extends Controller
{
    public function index(Request $request): View
    {
        Gate::authorize('viewAny', Project::class);

        $user = $request->user();

        $buscar = trim((string) $request->input('buscar', ''));
        $estado = $request->input('estado');

        $query = Project::query()->latest();

        if (! $user->hasRole('admin')) {
            $tieneFiltroDePropiedad =
                Schema::hasColumn('projects', 'owner_id') ||
                Schema::hasColumn('projects', 'user_id') ||
                method_exists(Project::class, 'members');

            if ($tieneFiltroDePropiedad) {
                $query->where(function ($q) use ($user) {
                    if (Schema::hasColumn('projects', 'owner_id')) {
                        $q->where('owner_id', $user->id);
                    }

                    if (Schema::hasColumn('projects', 'user_id')) {
                        $q->orWhere('user_id', $user->id);
                    }

                    if (method_exists(Project::class, 'members')) {
                        $q->orWhereHas('members', function ($memberQuery) use ($user) {
                            $memberQuery->where('users.id', $user->id);
                        });
                    }
                });
            }
        }

        if ($buscar !== '') {
            $query->where(function ($q) use ($buscar) {
                $q->where('nombre', 'ILIKE', '%' . $buscar . '%');

                if (Schema::hasColumn('projects', 'descripcion')) {
                    $q->orWhere('descripcion', 'ILIKE', '%' . $buscar . '%');
                }
            });
        }

        if (! empty($estado)) {
            $query->where('estado', $estado);
        }

        $projects = $query
            ->paginate(10)
            ->withQueryString();

        return view('projects.index', compact('projects', 'buscar', 'estado'));
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