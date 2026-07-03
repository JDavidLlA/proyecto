<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;

class ProjectMemberController extends Controller
{
    public function index(Project $project): View
    {
        Gate::authorize('update', $project);

        $project->load([
            'members.roles',
        ]);

        $users = User::query()
            ->with('roles')
            ->orderBy('name')
            ->get();

        $projectRoles = [
            'lider' => 'Líder',
            'colaborador' => 'Colaborador',
            'invitado' => 'Invitado',
        ];

        return view('projects.members.index', compact(
            'project',
            'users',
            'projectRoles'
        ));
    }

    public function store(Request $request, Project $project): RedirectResponse
    {
        Gate::authorize('update', $project);

        $data = $request->validate([
            'user_id' => ['required', 'exists:users,id'],
            'project_role' => ['required', 'in:lider,colaborador,invitado'],
        ]);

        $project->members()->syncWithoutDetaching([
            $data['user_id'] => [
                'project_role' => $data['project_role'],
            ],
        ]);

        return redirect()
            ->route('projects.members.index', $project)
            ->with('success', 'Usuario asignado al proyecto correctamente.');
    }

    public function update(Request $request, Project $project, User $user): RedirectResponse
    {
        Gate::authorize('update', $project);

        $data = $request->validate([
            'project_role' => ['required', 'in:lider,colaborador,invitado'],
        ]);

        if (! $project->members()->where('users.id', $user->id)->exists()) {
            return redirect()
                ->route('projects.members.index', $project)
                ->with('error', 'El usuario no pertenece a este proyecto.');
        }

        $project->members()->updateExistingPivot($user->id, [
            'project_role' => $data['project_role'],
        ]);

        return redirect()
            ->route('projects.members.index', $project)
            ->with('success', 'Rol del usuario actualizado correctamente.');
    }

    public function destroy(Project $project, User $user): RedirectResponse
    {
        Gate::authorize('update', $project);

        if ((int) $project->owner_id === (int) $user->id) {
            return redirect()
                ->route('projects.members.index', $project)
                ->with('error', 'No puedes quitar al dueño del proyecto.');
        }

        $project->members()->detach($user->id);

        return redirect()
            ->route('projects.members.index', $project)
            ->with('success', 'Usuario quitado del proyecto correctamente.');
    }
}