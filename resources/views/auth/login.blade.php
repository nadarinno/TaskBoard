@extends('layouts.app')

@section('title', 'Login')

@section('content')

<div class="auth-box">

    <div class="card">

        <h1>Login</h1>

        <form
            action="{{ route('login') }}"
            method="POST"
        >

            @csrf


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


            <button type="submit">
                Login
            </button>

        </form>


        <p>
            Don't have an account?

            <a href="{{ route('register') }}">
                Create Account
            </a>
        </p>

    </div>

</div>

@endsection