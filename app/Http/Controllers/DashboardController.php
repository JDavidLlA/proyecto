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
            ->whereIn('estado', ['pendiente'])
            ->count();

        $inProcessTasks = (clone $tasksQuery)
            ->whereIn('estado', ['en_proceso', 'en proceso', 'proceso'])
            ->count();

        $completedTasks = (clone $tasksQuery)
            ->whereIn('estado', ['completada', 'completado', 'finalizada', 'finalizado'])
            ->count();

        $totalComments = (clone $commentsQuery)->count();

        $latestProjectsQuery = $this->visibleProjectsQuery($user)->latest();

        if (method_exists(Project::class, 'tasks')) {
            $latestProjectsQuery->withCount('tasks');
        }

        $latestProjects = $latestProjectsQuery
            ->take(5)
            ->get();

        $latestTasksQuery = $this->visibleTasksQuery($user)->latest();

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
            'totalComments',
            'latestProjects',
            'latestTasks'
        ));
    }

    private function visibleProjectsQuery($user): Builder
    {
        $query = Project::query();

        if ($user->hasRole('admin')) {
            return $query;
        }

        $hasUserColumn = Schema::hasColumn('projects', 'user_id');
        $hasMembersRelation = method_exists(Project::class, 'members');

        if (! $hasUserColumn && ! $hasMembersRelation) {
            return $query->whereRaw('1 = 0');
        }

        return $query->where(function ($q) use ($user, $hasUserColumn, $hasMembersRelation) {
            if ($hasUserColumn) {
                $q->where('user_id', $user->id);
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
        $hasProjectRelation = method_exists(Task::class, 'project');

        if (! $hasTaskUserColumn && ! $hasProjectRelation) {
            return $query->whereRaw('1 = 0');
        }

        return $query->where(function ($q) use ($user, $hasTaskUserColumn, $hasProjectRelation) {
            if ($hasTaskUserColumn) {
                $q->where('user_id', $user->id);
            }

            if ($hasProjectRelation) {
                $q->orWhereHas('project', function ($projectQuery) use ($user) {
                    $hasProjectUserColumn = Schema::hasColumn('projects', 'user_id');
                    $hasMembersRelation = method_exists(Project::class, 'members');

                    if (! $hasProjectUserColumn && ! $hasMembersRelation) {
                        $projectQuery->whereRaw('1 = 0');
                        return;
                    }

                    $projectQuery->where(function ($q2) use ($user, $hasProjectUserColumn, $hasMembersRelation) {
                        if ($hasProjectUserColumn) {
                            $q2->where('user_id', $user->id);
                        }

                        if ($hasMembersRelation) {
                            $q2->orWhereHas('members', function ($memberQuery) use ($user) {
                                $memberQuery->where('users.id', $user->id);
                            });
                        }
                    });
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
                    $hasTaskProjectRelation = method_exists(Task::class, 'project');

                    if (! $hasTaskProjectRelation) {
                        $taskQuery->whereRaw('1 = 0');
                        return;
                    }

                    $taskQuery->whereHas('project', function ($projectQuery) use ($user) {
                        $hasProjectUserColumn = Schema::hasColumn('projects', 'user_id');
                        $hasMembersRelation = method_exists(Project::class, 'members');

                        if (! $hasProjectUserColumn && ! $hasMembersRelation) {
                            $projectQuery->whereRaw('1 = 0');
                            return;
                        }

                        $projectQuery->where(function ($q2) use ($user, $hasProjectUserColumn, $hasMembersRelation) {
                            if ($hasProjectUserColumn) {
                                $q2->where('user_id', $user->id);
                            }

                            if ($hasMembersRelation) {
                                $q2->orWhereHas('members', function ($memberQuery) use ($user) {
                                    $memberQuery->where('users.id', $user->id);
                                });
                            }
                        });
                    });
                });
            }
        });
    }
}