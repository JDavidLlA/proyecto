<?php

namespace App\Policies;

use App\Models\Project;
use App\Models\User;

class ProjectPolicy
{
    public function before(User $user, string $ability): bool|null
    {
        if ($user->hasRole('admin')) {
            return true;
        }

        return null;
    }

    public function viewAny(User $user): bool
    {
        return $user->can('projects.view');
    }

    public function view(User $user, Project $project): bool
    {
        return $user->can('projects.view') && $this->belongsToProject($user, $project);
    }

    public function create(User $user): bool
    {
        return $user->can('projects.create');
    }

    public function update(User $user, Project $project): bool
    {
        if (! $user->can('projects.update')) {
            return false;
        }

        if ($this->ownsProject($user, $project)) {
            return true;
        }

        if ($user->hasRole('lider') && $this->isProjectLeader($user, $project)) {
            return true;
        }

        return false;
    }

    public function delete(User $user, Project $project): bool
    {
        if (! $user->can('projects.delete')) {
            return false;
        }

        if ($this->ownsProject($user, $project)) {
            return true;
        }

        if ($user->hasRole('lider') && $this->isProjectLeader($user, $project)) {
            return true;
        }

        return false;
    }

    public function complete(User $user, Project $project): bool
    {
        if (! $user->can('projects.update')) {
            return false;
        }

        if ($project->estado === 'finalizado') {
            return false;
        }

        if ($user->hasRole('colaborador') || $user->hasRole('invitado')) {
            return false;
        }

        if ($user->hasRole('lider') && $this->isProjectLeader($user, $project)) {
            return true;
        }

        return false;
    }

    public function restore(User $user, Project $project): bool
    {
        return false;
    }

    public function forceDelete(User $user, Project $project): bool
    {
        return false;
    }

    private function ownsProject(User $user, Project $project): bool
    {
        if (! is_null($project->getAttribute('owner_id'))
            && (int) $project->getAttribute('owner_id') === (int) $user->id) {
            return true;
        }

        if (! is_null($project->getAttribute('user_id'))
            && (int) $project->getAttribute('user_id') === (int) $user->id) {
            return true;
        }

        return false;
    }

    private function belongsToProject(User $user, Project $project): bool
    {
        if ($this->ownsProject($user, $project)) {
            return true;
        }

        if (method_exists($project, 'members')) {
            return $project->members()
                ->where('users.id', $user->id)
                ->exists();
        }

        return false;
    }

    private function isProjectLeader(User $user, Project $project): bool
    {
        if ($this->ownsProject($user, $project)) {
            return true;
        }

        if (! method_exists($project, 'members')) {
            return false;
        }

        return $project->members()
            ->where('users.id', $user->id)
            ->wherePivot('project_role', 'lider')
            ->exists();
    }
}