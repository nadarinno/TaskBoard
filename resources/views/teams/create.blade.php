@extends('layouts.app')

@section('title', 'Create Team')

@section('content')

<div
    class="card"
    style="max-width: 600px; margin: auto;"
>

    <h1>Create Team</h1>

    <form
        action="{{ route('teams.store') }}"
        method="POST"
    >

        @csrf


        <label for="name">
            Team Name
        </label>

        <input
            type="text"
            id="name"
            name="name"
            value="{{ old('name') }}"
            placeholder="Example: Software Team"
            required
        >


        <button type="submit">
            Create Team
        </button>


        <a
            href="{{ route('teams.index') }}"
            class="btn btn-secondary"
        >
            Cancel
        </a>

    </form>

</div>

@endsection