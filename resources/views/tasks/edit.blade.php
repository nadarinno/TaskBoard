@extends('layouts.app')

@section('title', 'Edit Task')

@section('content')

<div
    class="card"
    style="max-width: 750px; margin: auto;"
>

    <h1>Edit Task</h1>


    <form
        action="{{ route('tasks.update', $task) }}"
        method="POST"
        enctype="multipart/form-data"
    >

        @csrf
        @method('PUT')


        <label for="title">
            Title
        </label>

        <input
            type="text"
            name="title"
            id="title"
            value="{{ old('title', $task->title) }}"
            required
        >


        <label for="description">
            Description
        </label>

        <textarea
            name="description"
            id="description"
            rows="5"
        >{{ old('description', $task->description) }}</textarea>


        <label for="due_date">
            Due Date
        </label>

        <input
            type="date"
            name="due_date"
            id="due_date"
            value="{{ old(
                'due_date',
                $task->due_date->format('Y-m-d')
            ) }}"
            required
        >


        <label for="label">
            Label
        </label>

        <input
            type="text"
            name="label"
            id="label"
            value="{{ old(
                'label',
                $task->label
            ) }}"
        >


        <h3>Assigned Members</h3>


        @php

            $assignedIds = old(
                'assignees',
                $task->assignees
                    ->pluck('id')
                    ->toArray()
            );

        @endphp


        @foreach($task->team->users as $user)

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
                            $assignedIds
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


        <label>
            Add More Attachments
        </label>

        <input
            type="file"
            name="attachments[]"
            multiple
        >


        <h3>Current Attachments</h3>


        @forelse(
            $task->attachments as $attachment
        )

            <p>
                {{ $attachment->file_name }}
            </p>

        @empty

            <p>
                No attachments.
            </p>

        @endforelse


        <br>


        <button type="submit">
            Save Changes
        </button>


        <a
            href="{{ route('tasks.show', $task) }}"
            class="btn btn-secondary"
        >
            Cancel
        </a>

    </form>

</div>

@endsection