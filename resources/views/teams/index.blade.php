@extends('layouts.app')

@section('title', 'My Teams')

@section('content')

<div style="
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 25px;
">

    <div>
        <h1>My Teams</h1>

        <p>
            Teams you are currently a member of.
        </p>
    </div>


    <a
        href="{{ route('teams.create') }}"
        class="btn"
    >
        + Create Team
    </a>

</div>


@if($teams->isEmpty())

    <div class="card">

        <h3>No teams yet</h3>

        <p>
            Create your first team to start managing tasks.
        </p>

        <a
            href="{{ route('teams.create') }}"
            class="btn"
        >
            Create Team
        </a>

    </div>

@else

    <div class="teams-grid">

        @foreach($teams as $team)

            <a
                href="{{ route('teams.show', $team) }}"
                class="team-card"
            >

                <h2>
                    {{ $team->name }}
                </h2>

                <p>
                    Members:
                    {{ $team->users_count }}
                </p>

                <p>
                    Tasks:
                    {{ $team->tasks_count }}
                </p>

            </a>

        @endforeach

    </div>

@endif

@endsection