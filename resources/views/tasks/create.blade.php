@extends('layouts.app')

@section('title', 'Create Task')

@section('content')

<div
    class="card"
    style="max-width: 750px; margin: auto;"
>

    <h1>Create Task</h1>

    <p>
        Team:
        <strong>
            {{ $team->name }}
        </strong>
    </p>


    <form
        action="{{ route('tasks.store', $team) }}"
        method="POST"
        enctype="multipart/form-data"
    >

        @csrf


        <label for="title">
            Title
        </label>

        <input
            type="text"
            id="title"
            name="title"
            value="{{ old('title') }}"
            required
        >


        <label for="description">
            Description
        </label>

        <textarea
            id="description"
            name="description"
            rows="5"
        >{{ old('description') }}</textarea>


        <label for="due_date">
            Due Date
        </label>

        <input
            type="date"
            id="due_date"
            name="due_date"
            value="{{ old('due_date') }}"
            required
        >


        <label for="label">
            Label
        </label>

        <input
            type="text"
            id="label"
            name="label"
            value="{{ old('label') }}"
            placeholder="Example: Important"
        >


        <label for="status_id">
            Status
        </label>

        <select
            id="status_id"
            name="status_id"
            required
        >

            @foreach($statuses as $status)

                <option
                    value="{{ $status->id }}"
                    @selected(
                        old('status_id')
                        == $status->id
                    )
                >
                    {{ $status->name }}
                </option>

            @endforeach

        </select>


        <h3>Assign Members</h3>

        <p>
            You can assign this task to multiple users.
        </p>


        @foreach($team->users as $user)

            <label
                style="
                    display: block;
                    font-weight: normal;
                    margin-bottom: 10px;
                "
            >

                <input
                    type="checkbox"
                    name="assignees[]"
                    value="{{ $user->id }}"
                    style="width: auto;"
                    @checked(
                        in_array(
                            $user->id,
                            old('assignees', [])
                        )
                    )
                >

                {{ $user->name }}

                <small>
                    {{ $user->email }}
                </small>

            </label>

        @endforeach


        <br>


        <label for="attachments">
            Attachments
        </label>

        <input
            type="file"
            id="attachments"
            name="attachments[]"
            multiple
        >

        <small>
            You can select one or more files.
            Maximum 5 MB per file.
        </small>


        <br><br>


        <button type="submit">
            Create Task
        </button>


        <a
            href="{{ route('teams.show', $team) }}"
            class="btn btn-secondary"
        >
            Cancel
        </a>

    </form>

</div>

@endsection