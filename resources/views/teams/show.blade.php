@extends('layouts.app')

@section('title', $team->name)

@section('content')


<div
    style="
        display: flex;
        justify-content: space-between;
        align-items: center;
    "
>

    <div>

        <h1>
            {{ $team->name }}
        </h1>

        <p>
            Created by:

            <strong>
                {{ $team->creator->name }}
            </strong>
        </p>

    </div>


    <a
        href="{{ route('teams.index') }}"
        class="btn btn-secondary"
    >
        Back to Teams
    </a>

</div>


<div class="card">

    <h2>Team Members</h2>


    <div class="members">

        @foreach(
            $team->users as $user
        )

            <div class="member">

                {{ $user->name }}

                <small>
                    {{ $user->email }}
                </small>


                @if(
                    $user->id
                    ===
                    $team->created_by
                )

                    <strong>
                        - Creator
                    </strong>

                @endif

            </div>

        @endforeach

    </div>

</div>


@if(
    $team->created_by
    ===
    auth()->id()
)

    <div class="card">

        <h2>Add Member</h2>

        <p>
            The user must already have an account
            on the platform.
        </p>


        <form
            action="{{ route(
                'teams.members.add',
                $team
            ) }}"
            method="POST"
        >

            @csrf


            <label for="email">
                User Email
            </label>


            <input
                type="email"
                name="email"
                id="email"
                value="{{ old('email') }}"
                placeholder="user@example.com"
                required
            >


            <button type="submit">
                Add Member
            </button>

        </form>

    </div>

@endif


<div
    style="
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-top: 30px;
    "
>

    <h2>
        Task Board
    </h2>


    <a
        href="{{ route(
            'tasks.create',
            $team
        ) }}"
        class="btn"
    >
        + Create Task
    </a>

</div>


<div class="board">

    @foreach(
        $statuses as $status
    )

        <div class="status-column">

            <h3>
                {{ $status->name }}
            </h3>


            @php

                $statusTasks =
                    $team->tasks
                        ->where(
                            'status_id',
                            $status->id
                        );

            @endphp


            @forelse(
                $statusTasks as $task
            )

                <div class="task-card">

                    <a
                        href="{{ route(
                            'tasks.show',
                            $task
                        ) }}"
                        style="
                            color: inherit;
                            text-decoration: none;
                        "
                    >

                        <strong>
                            {{ $task->title }}
                        </strong>


                        @if($task->label)

                            <p>
                                <small>
                                    Label:
                                    {{ $task->label }}
                                </small>
                            </p>

                        @endif


                        <p>
                            {{
                                \Illuminate\Support\Str::limit(
                                    $task->description,
                                    80
                                )
                            }}
                        </p>


                        <small>
                            Created by:
                            {{ $task->creator->name }}
                        </small>


                        <br>


                        <small>
                            Due:

                            {{
                                $task->due_date
                                    ->format(
                                        'Y-m-d'
                                    )
                            }}
                        </small>


                        @if(
                            $task
                                ->assignees
                                ->isNotEmpty()
                        )

                            <p>
                                <small>
                                    Assigned:

                                    {{
                                        $task
                                            ->assignees
                                            ->pluck(
                                                'name'
                                            )
                                            ->join(', ')
                                    }}
                                </small>
                            </p>

                        @endif


                        @if($task->closed_at)

                            <p
                                style="
                                    color: #c9372c;
                                    font-weight: bold;
                                "
                            >
                                CLOSED
                            </p>

                        @endif

                    </a>

                </div>

            @empty

                <p>
                    No tasks
                </p>

            @endforelse

        </div>

    @endforeach

</div>

@endsection