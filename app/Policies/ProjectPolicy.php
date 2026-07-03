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
        return $user->can('projects.update') && (
            $user->hasRole('lider') ||
            $this->belongsToProject($user, $project)
        );
    }

    public function delete(User $user, Project $project): bool
    {
        return $user->can('projects.delete') && (
            $user->hasRole('lider') ||
            $this->ownsProject($user, $project)
        );
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
        return ! is_null($project->getAttribute('user_id'))
            && (int) $project->getAttribute('user_id') === (int) $user->id;
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
}