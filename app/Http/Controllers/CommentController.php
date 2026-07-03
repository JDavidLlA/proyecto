<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCommentRequest;
use App\Http\Requests\UpdateCommentRequest;
use App\Models\Comment;
use App\Models\Project;
use App\Models\Task;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;

class CommentController extends Controller
{
    public function create(Project $project, Task $task): View
    {
        $this->ensureTaskBelongsToProject($project, $task);

        Gate::authorize('create', [Comment::class, $task]);

        return view('comments.create', compact('project', 'task'));
    }

    public function store(StoreCommentRequest $request, Project $project, Task $task): RedirectResponse
    {
        $this->ensureTaskBelongsToProject($project, $task);

        Gate::authorize('create', [Comment::class, $task]);

        Comment::create([
            'task_id' => $task->id,
            'user_id' => auth()->id(),
            'cuerpo' => $request->validated()['cuerpo'],
        ]);

        return redirect()
            ->route('projects.tasks.show', [$project, $task])
            ->with('success', 'Comentario creado correctamente.');
    }

    public function show(Project $project, Task $task, Comment $comment): View
    {
        $this->ensureTaskBelongsToProject($project, $task);
        $this->ensureCommentBelongsToTask($task, $comment);

        Gate::authorize('view', $comment);

        $comment->load('user');

        return view('comments.show', compact('project', 'task', 'comment'));
    }

    public function edit(Project $project, Task $task, Comment $comment): View
    {
        $this->ensureTaskBelongsToProject($project, $task);
        $this->ensureCommentBelongsToTask($task, $comment);

        Gate::authorize('update', $comment);

        return view('comments.edit', compact('project', 'task', 'comment'));
    }

    public function update(UpdateCommentRequest $request, Project $project, Task $task, Comment $comment): RedirectResponse
    {
        $this->ensureTaskBelongsToProject($project, $task);
        $this->ensureCommentBelongsToTask($task, $comment);

        Gate::authorize('update', $comment);

        $comment->update($request->validated());

        return redirect()
            ->route('projects.tasks.show', [$project, $task])
            ->with('success', 'Comentario actualizado correctamente.');
    }

    public function destroy(Project $project, Task $task, Comment $comment): RedirectResponse
    {
        $this->ensureTaskBelongsToProject($project, $task);
        $this->ensureCommentBelongsToTask($task, $comment);

        Gate::authorize('delete', $comment);

        $comment->delete();

        return redirect()
            ->route('projects.tasks.show', [$project, $task])
            ->with('success', 'Comentario eliminado correctamente.');
    }

    private function ensureTaskBelongsToProject(Project $project, Task $task): void
    {
        abort_unless((int) $task->project_id === (int) $project->id, 404);
    }

    private function ensureCommentBelongsToTask(Task $task, Comment $comment): void
    {
        abort_unless((int) $comment->task_id === (int) $task->id, 404);
    }
}