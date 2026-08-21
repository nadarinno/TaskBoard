@extends('layouts.app')

@section('title', 'Register')

@section('content')

<div class="auth-box">

    <div class="card">

        <h1>Create Account</h1>

        <p>
            Create an account to start managing your teams and tasks.
        </p>

        <form
            action="{{ route('register') }}"
            method="POST"
        >

            @csrf

            <label for="name">
                Name
            </label>

            <input
                type="text"
                id="name"
                name="name"
                value="{{ old('name') }}"
                required
            >


            <label for="email">
                Email
            </label>

            <input
                type="email"
                id="email"
                name="email"
                value="{{ old('email') }}"
                required
            >


            <label for="password">
                Password
            </label>

            <input
                type="password"
                id="password"
                name="password"
                required
            >


            <label for="password_confirmation">
                Confirm Password
            </label>

            <input
                type="password"
                id="password_confirmation"
                name="password_confirmation"
                required
            >


            <button type="submit">
                Register
            </button>

        </form>

        <p>
            Already have an account?

            <a href="{{ route('login') }}">
                Login
            </a>
        </p>

    </div>

</div>

@endsection