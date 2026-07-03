<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Project;
use App\Models\Task;
use App\Models\User;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $totalUsers = User::count();
        $totalProjects = Project::count();
        $totalTasks = Task::count();
        $totalComments = Comment::count();

        $latestProjects = Project::with('owner')
            ->withCount(['tasks', 'members'])
            ->latest()
            ->take(5)
            ->get();

        return view('dashboard', compact(
            'totalUsers',
            'totalProjects',
            'totalTasks',
            'totalComments',
            'latestProjects'
        ));
    }
}