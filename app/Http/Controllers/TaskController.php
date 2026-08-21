<?php

namespace App\Http\Controllers;

use App\Models\Status;
use App\Models\Task;
use App\Models\TaskAttachment;
use App\Models\Team;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class TaskController extends Controller
{
    public function create(Team $team)
    {
        $this->ensureTeamMember($team);

        $team->load('users');

        $statuses = Status::orderBy('order')->get();

        return view('tasks.create', compact('team', 'statuses'));
    }

    public function store(Request $request, Team $team)
    {
        $this->ensureTeamMember($team);

        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'due_date' => ['required', 'date'],
            'label' => ['nullable', 'string', 'max:100'],

            'status_id' => [
                'required',
                'exists:statuses,id',
            ],

            'assignees' => [
                'nullable',
                'array',
            ],

            'assignees.*' => [
                'integer',
                'exists:users,id',
            ],

            'attachments' => [
                'nullable',
                'array',
            ],

            'attachments.*' => [
                'file',
                'max:5120',
            ],
        ]);

        $assigneeIds = collect(
            $validated['assignees'] ?? []
        )->unique()->values();

        if ($assigneeIds->isNotEmpty()) {

            $teamMemberCount = $team->users()
                ->whereIn('users.id', $assigneeIds)
                ->count();

            if ($teamMemberCount !== $assigneeIds->count()) {

                return back()
                    ->withInput()
                    ->withErrors([
                        'assignees' =>
                            'All assigned users must be members of this team.',
                    ]);
            }
        }

        $task = Task::create([
            'team_id' => $team->id,
            'created_by' => auth()->id(),
            'status_id' => $validated['status_id'],
            'title' => $validated['title'],
            'description' => $validated['description'] ?? null,
            'due_date' => $validated['due_date'],
            'label' => $validated['label'] ?? null,
        ]);

        $task->assignees()->sync(
            $assigneeIds->all()
        );

        if ($request->hasFile('attachments')) {

            foreach ($request->file('attachments') as $file) {

                $path = $file->store(
                    'task-attachments',
                    'public'
                );

                TaskAttachment::create([
                    'task_id' => $task->id,
                    'file_name' => $file->getClientOriginalName(),
                    'file_path' => $path,
                ]);
            }
        }

        return redirect()
            ->route('tasks.show', $task)
            ->with(
                'success',
                'Task created successfully.'
            );
    }

    public function show(Task $task)
    {
        $this->ensureTeamMember($task->team);

        $task->load([
            'team',
            'creator',
            'status',
            'assignees',
            'attachments',
            'comments.user',
        ]);

        $statuses = Status::orderBy('order')->get();

        return view(
            'tasks.show',
            compact('task', 'statuses')
        );
    }

    public function edit(Task $task)
    {
        $this->ensureTeamMember($task->team);

        if ($task->created_by !== auth()->id()) {
            abort(
                403,
                'Only the task creator can edit this task.'
            );
        }

        if ($task->closed_at) {

            return redirect()
                ->route('tasks.show', $task)
                ->withErrors([
                    'task' =>
                        'Closed tasks cannot be edited.',
                ]);
        }

        $task->load([
            'team.users',
            'assignees',
            'attachments',
        ]);

        $statuses = Status::orderBy('order')->get();

        return view(
            'tasks.edit',
            compact('task', 'statuses')
        );
    }

    public function update(
        Request $request,
        Task $task
    ) {
        $this->ensureTeamMember($task->team);

        if ($task->created_by !== auth()->id()) {
            abort(
                403,
                'Only the task creator can edit this task.'
            );
        }

        if ($task->closed_at) {

            return back()->withErrors([
                'task' =>
                    'Closed tasks cannot be edited.',
            ]);
        }

        $validated = $request->validate([
            'title' => [
                'required',
                'string',
                'max:255',
            ],

            'description' => [
                'nullable',
                'string',
            ],

            'due_date' => [
                'required',
                'date',
            ],

            'label' => [
                'nullable',
                'string',
                'max:100',
            ],

            'assignees' => [
                'nullable',
                'array',
            ],

            'assignees.*' => [
                'integer',
                'exists:users,id',
            ],

            'attachments' => [
                'nullable',
                'array',
            ],

            'attachments.*' => [
                'file',
                'max:5120',
            ],
        ]);

        $assigneeIds = collect(
            $validated['assignees'] ?? []
        )->unique()->values();

        if ($assigneeIds->isNotEmpty()) {

            $teamMemberCount = $task->team
                ->users()
                ->whereIn(
                    'users.id',
                    $assigneeIds
                )
                ->count();

            if (
                $teamMemberCount
                !==
                $assigneeIds->count()
            ) {

                return back()
                    ->withInput()
                    ->withErrors([
                        'assignees' =>
                            'All assigned users must be members of this team.',
                    ]);
            }
        }

        $task->update([
            'title' => $validated['title'],
            'description' =>
                $validated['description'] ?? null,
            'due_date' => $validated['due_date'],
            'label' =>
                $validated['label'] ?? null,
        ]);

        $task->assignees()->sync(
            $assigneeIds->all()
        );

        if ($request->hasFile('attachments')) {

            foreach ($request->file('attachments') as $file) {

                $path = $file->store(
                    'task-attachments',
                    'public'
                );

                TaskAttachment::create([
                    'task_id' => $task->id,
                    'file_name' =>
                        $file->getClientOriginalName(),
                    'file_path' => $path,
                ]);
            }
        }

        return redirect()
            ->route('tasks.show', $task)
            ->with(
                'success',
                'Task updated successfully.'
            );
    }

    public function destroy(Task $task)
    {
        $this->ensureTeamMember($task->team);

        if ($task->created_by !== auth()->id()) {
            abort(
                403,
                'Only the task creator can delete this task.'
            );
        }

        $teamId = $task->team_id;

        foreach ($task->attachments as $attachment) {

            Storage::disk('public')
                ->delete(
                    $attachment->file_path
                );
        }

        $task->delete();

        return redirect()
            ->route('teams.show', $teamId)
            ->with(
                'success',
                'Task deleted successfully.'
            );
    }

    public function move(
        Request $request,
        Task $task
    ) {
        $this->ensureTeamMember($task->team);

        if ($task->closed_at) {

            return back()->withErrors([
                'task' =>
                    'Closed tasks cannot be moved.',
            ]);
        }

        $validated = $request->validate([
            'status_id' => [
                'required',
                'exists:statuses,id',
            ],
        ]);

        $task->update([
            'status_id' =>
                $validated['status_id'],
        ]);

        return back()->with(
            'success',
            'Task moved successfully.'
        );
    }

    public function close(Task $task)
    {
        $this->ensureTeamMember($task->team);

        if ($task->closed_at) {

            return back()->withErrors([
                'task' =>
                    'This task is already closed.',
            ]);
        }

        $task->update([
            'closed_at' => now(),
        ]);

        return back()->with(
            'success',
            'Task closed successfully.'
        );
    }

    private function ensureTeamMember(
        Team $team
    ): void {
        $isMember = $team->users()
            ->where(
                'users.id',
                auth()->id()
            )
            ->exists();

        if (! $isMember) {

            abort(
                403,
                'You are not a member of this team.'
            );
        }
    }
}