@extends('layouts.app')

@section('title', $task->title)

@section('content')


<div
    style="
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 15px;
    "
>

    <div>

        <h1>
            {{ $task->title }}
        </h1>

        <p>
            Team:

            <a
                href="{{ route(
                    'teams.show',
                    $task->team
                ) }}"
            >
                {{ $task->team->name }}
            </a>
        </p>

    </div>


    <a
        href="{{ route(
            'teams.show',
            $task->team
        ) }}"
        class="btn btn-secondary"
    >
        Back to Board
    </a>

</div>


@if($task->closed_at)

    <div class="error">

        <strong>
            This task is closed.
        </strong>

        <br>

        Closed at:

        {{ $task->closed_at->format(
            'Y-m-d H:i'
        ) }}

    </div>

@endif


<div class="card">

    <h2>Task Details</h2>


    <p>
        <strong>Status:</strong>

        {{ $task->status->name }}
    </p>


    <p>
        <strong>Created By:</strong>

        {{ $task->creator->name }}
    </p>


    <p>
        <strong>Due Date:</strong>

        {{ $task->due_date->format(
            'Y-m-d'
        ) }}
    </p>


    <p>
        <strong>Label:</strong>

        {{ $task->label ?? 'No label' }}
    </p>


    <p>
        <strong>Description:</strong>
    </p>

    <p>
        {{ $task->description
            ?? 'No description.' }}
    </p>

</div>


<div class="card">

    <h2>Assigned Members</h2>


    @forelse(
        $task->assignees as $user
    )

        <div
            class="member"
            style="
                display: inline-block;
                margin: 5px;
            "
        >

            {{ $user->name }}

            <small>
                {{ $user->email }}
            </small>

        </div>

    @empty

        <p>
            No users assigned to this task.
        </p>

    @endforelse

</div>


<div class="card">

    <h2>Attachments</h2>


    @forelse(
        $task->attachments as $attachment
    )

        <div
            style="margin-bottom: 10px;"
        >

            <a
                href="{{ asset(
                    'storage/'
                    . $attachment->file_path
                ) }}"
                target="_blank"
            >
                {{ $attachment->file_name }}
            </a>

        </div>

    @empty

        <p>
            No attachments.
        </p>

    @endforelse

</div>


@if(!$task->closed_at)

    <div class="card">

        <h2>Move Task</h2>

        <p>
            Any team member can move this task.
        </p>


        <form
            action="{{ route(
                'tasks.move',
                $task
            ) }}"
            method="POST"
        >

            @csrf
            @method('PATCH')


            <label for="status_id">
                Status
            </label>


            <select
                id="status_id"
                name="status_id"
                required
            >

                @foreach(
                    $statuses as $status
                )

                    <option
                        value="{{ $status->id }}"
                        @selected(
                            $task->status_id
                            ===
                            $status->id
                        )
                    >
                        {{ $status->name }}
                    </option>

                @endforeach

            </select>


            <button type="submit">
                Move Task
            </button>

        </form>

    </div>

@endif


@if(
    $task->created_by
    ===
    auth()->id()
)

    <div class="card">

        <h2>Task Creator Actions</h2>


        @if(!$task->closed_at)

            <a
                href="{{ route(
                    'tasks.edit',
                    $task
                ) }}"
                class="btn"
            >
                Edit Task
            </a>

        @endif


        <form
            action="{{ route(
                'tasks.destroy',
                $task
            ) }}"
            method="POST"
            style="
                display: inline-block;
                margin-left: 10px;
            "
        >

            @csrf
            @method('DELETE')


            <button
                type="submit"
                class="btn-danger"
                onclick="
                    return confirm(
                        'Are you sure you want to delete this task?'
                    )
                "
            >
                Delete Task
            </button>

        </form>

    </div>

@endif


@if(!$task->closed_at)

    <div class="card">

        <h2>Close Task</h2>

        <p>
            Once this task is closed,
            it cannot be reopened.
        </p>


        <form
            action="{{ route(
                'tasks.close',
                $task
            ) }}"
            method="POST"
        >

            @csrf
            @method('PATCH')


            <button
                type="submit"
                class="btn-danger"
                onclick="
                    return confirm(
                        'Close this task permanently?'
                    )
                "
            >
                Close Task Permanently
            </button>

        </form>

    </div>

@endif


@endsection