<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreProjectRequest;
use App\Http\Requests\UpdateProjectRequest;
use App\Models\Project;
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
        $prioridad = $request->input('prioridad');

        $estados = [
            'activo' => 'Activo',
            'pausado' => 'Pausado',
            'finalizado' => 'Finalizado',
        ];

        $prioridades = [
            'baja' => 'Baja',
            'media' => 'Media',
            'alta' => 'Alta',
            'urgente' => 'Urgente',
        ];

        $query = Project::query()
            ->with('owner')
            ->latest();

        if (method_exists(Project::class, 'completedBy')) {
            $query->with('completedBy');
        }

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

        if (! empty($estado) && array_key_exists($estado, $estados)) {
            $query->where('estado', $estado);
        }

        if (
            ! empty($prioridad) &&
            array_key_exists($prioridad, $prioridades) &&
            Schema::hasColumn('projects', 'prioridad')
        ) {
            $query->where('prioridad', $prioridad);
        }

        if (Schema::hasColumn('projects', 'prioridad')) {
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

        $projects = $query
            ->paginate(10)
            ->withQueryString();

        return view('projects.index', compact(
            'projects',
            'buscar',
            'estado',
            'prioridad',
            'estados',
            'prioridades'
        ));
    }

    public function create(): View
    {
        Gate::authorize('projects.create');

        $estados = [
            'activo' => 'Activo',
            'pausado' => 'Pausado',
            'finalizado' => 'Finalizado',
        ];

        $prioridades = [
            'baja' => 'Baja',
            'media' => 'Media',
            'alta' => 'Alta',
            'urgente' => 'Urgente',
        ];

        return view('projects.create', compact('estados', 'prioridades'));
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

        if (Schema::hasColumn('projects', 'prioridad')) {
            $project->prioridad = $data['prioridad'] ?? 'media';
        }

        if (Schema::hasColumn('projects', 'user_id')) {
            $project->user_id = $request->user()->id;
        }

        if (($data['estado'] ?? null) === 'finalizado') {
            if (Schema::hasColumn('projects', 'completed_by')) {
                $project->completed_by = $request->user()->id;
            }

            if (Schema::hasColumn('projects', 'completed_at')) {
                $project->completed_at = now();
            }
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

        if (method_exists(Project::class, 'completedBy')) {
            $project->load('completedBy');
        }

        if (method_exists(Project::class, 'owner')) {
            $project->load('owner');
        }

        if (method_exists(Project::class, 'tasks')) {
            $project->load([
                'tasks' => function ($query) {
                    if (method_exists(\App\Models\Task::class, 'assignee')) {
                        $query->with('assignee');
                    }

                    if (method_exists(\App\Models\Task::class, 'completedBy')) {
                        $query->with('completedBy');
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
                },
            ]);
        }

        if (method_exists(Project::class, 'members')) {
            $project->load('members');
        }

        return view('projects.show', compact('project'));
    }

    public function edit(Project $project): View
    {
        Gate::authorize('update', $project);

        $estados = [
            'activo' => 'Activo',
            'pausado' => 'Pausado',
            'finalizado' => 'Finalizado',
        ];

        $prioridades = [
            'baja' => 'Baja',
            'media' => 'Media',
            'alta' => 'Alta',
            'urgente' => 'Urgente',
        ];

        return view('projects.edit', compact('project', 'estados', 'prioridades'));
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

        if (Schema::hasColumn('projects', 'prioridad')) {
            $project->prioridad = $data['prioridad'] ?? 'media';
        }

        if (($data['estado'] ?? null) === 'finalizado') {
            if (Schema::hasColumn('projects', 'completed_by') && empty($project->completed_by)) {
                $project->completed_by = $request->user()->id;
            }

            if (Schema::hasColumn('projects', 'completed_at') && empty($project->completed_at)) {
                $project->completed_at = now();
            }
        }

        if (($data['estado'] ?? null) !== 'finalizado') {
            if (Schema::hasColumn('projects', 'completed_by')) {
                $project->completed_by = null;
            }

            if (Schema::hasColumn('projects', 'completed_at')) {
                $project->completed_at = null;
            }
        }

        $project->save();

        return redirect()
            ->route('projects.show', $project)
            ->with('success', 'Proyecto actualizado correctamente.');
    }

    public function complete(Request $request, Project $project): RedirectResponse
    {
        Gate::authorize('complete', $project);

        $project->estado = 'finalizado';

        if (Schema::hasColumn('projects', 'completed_by')) {
            $project->completed_by = $request->user()->id;
        }

        if (Schema::hasColumn('projects', 'completed_at')) {
            $project->completed_at = now();
        }

        $project->save();

        return redirect()
            ->route('projects.show', $project)
            ->with('success', 'Proyecto finalizado correctamente.');
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