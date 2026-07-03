<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Project;
use App\Models\Task;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Schema;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $user = auth()->user();

        $projectsQuery = $this->visibleProjectsQuery($user);
        $tasksQuery = $this->visibleTasksQuery($user);
        $commentsQuery = $this->visibleCommentsQuery($user);

        $totalProjects = (clone $projectsQuery)->count();

        $totalTasks = (clone $tasksQuery)->count();

        $pendingTasks = (clone $tasksQuery)
            ->where('estado', 'pendiente')
            ->count();

        $inProcessTasks = (clone $tasksQuery)
            ->where('estado', 'en_progreso')
            ->count();

        $completedTasks = (clone $tasksQuery)
            ->where('estado', 'completada')
            ->count();

        $highPriorityTasksCount = 0;
        $urgentPriorityTasksCount = 0;
        $priorityTasks = collect();
        $firstProjectWithPriorityTasks = null;

        if (Schema::hasColumn('tasks', 'prioridad')) {
            $highPriorityTasksCount = (clone $tasksQuery)
                ->where('prioridad', 'alta')
                ->count();

            $urgentPriorityTasksCount = (clone $tasksQuery)
                ->where('prioridad', 'urgente')
                ->count();

            $priorityTasksQuery = $this->visibleTasksQuery($user)
                ->whereIn('prioridad', ['alta', 'urgente'])
                ->orderByRaw("
                    CASE prioridad
                        WHEN 'urgente' THEN 1
                        WHEN 'alta' THEN 2
                        ELSE 3
                    END
                ")
                ->latest();

            if (method_exists(Task::class, 'project')) {
                $priorityTasksQuery->with('project');
            }

            if (method_exists(Task::class, 'comments')) {
                $priorityTasksQuery->withCount('comments');
            }

            $priorityTasks = $priorityTasksQuery
                ->take(5)
                ->get();

            $firstProjectWithPriorityTasks = $priorityTasks->first()?->project_id;
        }

        $totalComments = (clone $commentsQuery)->count();

        $latestProjectsQuery = $this->visibleProjectsQuery($user)
            ->latest();

        if (method_exists(Project::class, 'tasks')) {
            $latestProjectsQuery->withCount('tasks');
        }

        $latestProjects = $latestProjectsQuery
            ->take(5)
            ->get();

        $latestTasksQuery = $this->visibleTasksQuery($user)
            ->latest();

        if (method_exists(Task::class, 'project')) {
            $latestTasksQuery->with('project');
        }

        if (method_exists(Task::class, 'comments')) {
            $latestTasksQuery->withCount('comments');
        }

        $latestTasks = $latestTasksQuery
            ->take(5)
            ->get();

        return view('dashboard', compact(
            'totalProjects',
            'totalTasks',
            'pendingTasks',
            'inProcessTasks',
            'completedTasks',
            'highPriorityTasksCount',
            'urgentPriorityTasksCount',
            'totalComments',
            'latestProjects',
            'latestTasks',
            'priorityTasks',
            'firstProjectWithPriorityTasks'
        ));
    }

    private function visibleProjectsQuery($user): Builder
    {
        $query = Project::query();

        if ($user->hasRole('admin')) {
            return $query;
        }

        $hasOwnerColumn = Schema::hasColumn('projects', 'owner_id');
        $hasUserColumn = Schema::hasColumn('projects', 'user_id');
        $hasMembersRelation = method_exists(Project::class, 'members');

        if (! $hasOwnerColumn && ! $hasUserColumn && ! $hasMembersRelation) {
            return $query->whereRaw('1 = 0');
        }

        return $query->where(function ($q) use ($user, $hasOwnerColumn, $hasUserColumn, $hasMembersRelation) {
            if ($hasOwnerColumn) {
                $q->where('owner_id', $user->id);
            }

            if ($hasUserColumn) {
                $q->orWhere('user_id', $user->id);
            }

            if ($hasMembersRelation) {
                $q->orWhereHas('members', function ($memberQuery) use ($user) {
                    $memberQuery->where('users.id', $user->id);
                });
            }
        });
    }

    private function visibleTasksQuery($user): Builder
    {
        $query = Task::query();

        if ($user->hasRole('admin')) {
            return $query;
        }

        $hasTaskUserColumn = Schema::hasColumn('tasks', 'user_id');
        $hasAssigneeColumn = Schema::hasColumn('tasks', 'assignee_id');
        $hasProjectRelation = method_exists(Task::class, 'project');

        if (! $hasTaskUserColumn && ! $hasAssigneeColumn && ! $hasProjectRelation) {
            return $query->whereRaw('1 = 0');
        }

        return $query->where(function ($q) use ($user, $hasTaskUserColumn, $hasAssigneeColumn, $hasProjectRelation) {
            if ($hasTaskUserColumn) {
                $q->where('user_id', $user->id);
            }

            if ($hasAssigneeColumn) {
                $q->orWhere('assignee_id', $user->id);
            }

            if ($hasProjectRelation) {
                $q->orWhereHas('project', function ($projectQuery) use ($user) {
                    $this->applyProjectVisibility($projectQuery, $user);
                });
            }
        });
    }

    private function visibleCommentsQuery($user): Builder
    {
        $query = Comment::query();

        if ($user->hasRole('admin')) {
            return $query;
        }

        $hasCommentUserColumn = Schema::hasColumn('comments', 'user_id');
        $hasTaskRelation = method_exists(Comment::class, 'task');

        if (! $hasCommentUserColumn && ! $hasTaskRelation) {
            return $query->whereRaw('1 = 0');
        }

        return $query->where(function ($q) use ($user, $hasCommentUserColumn, $hasTaskRelation) {
            if ($hasCommentUserColumn) {
                $q->where('user_id', $user->id);
            }

            if ($hasTaskRelation) {
                $q->orWhereHas('task', function ($taskQuery) use ($user) {
                    if (! method_exists(Task::class, 'project')) {
                        $taskQuery->whereRaw('1 = 0');
                        return;
                    }

                    $taskQuery->whereHas('project', function ($projectQuery) use ($user) {
                        $this->applyProjectVisibility($projectQuery, $user);
                    });
                });
            }
        });
    }

    private function applyProjectVisibility(Builder $projectQuery, $user): void
    {
        $hasOwnerColumn = Schema::hasColumn('projects', 'owner_id');
        $hasUserColumn = Schema::hasColumn('projects', 'user_id');
        $hasMembersRelation = method_exists(Project::class, 'members');

        if (! $hasOwnerColumn && ! $hasUserColumn && ! $hasMembersRelation) {
            $projectQuery->whereRaw('1 = 0');
            return;
        }

        $projectQuery->where(function ($q) use ($user, $hasOwnerColumn, $hasUserColumn, $hasMembersRelation) {
            if ($hasOwnerColumn) {
                $q->where('owner_id', $user->id);
            }

            if ($hasUserColumn) {
                $q->orWhere('user_id', $user->id);
            }

            if ($hasMembersRelation) {
                $q->orWhereHas('members', function ($memberQuery) use ($user) {
                    $memberQuery->where('users.id', $user->id);
                });
            }
        });
    }
}