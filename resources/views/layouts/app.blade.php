<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>@yield('title', 'TaskBoard')</title>

    <style>
        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background: #f4f5f7;
            color: #172b4d;
        }

        nav {
            background: #172b4d;
            color: white;
            padding: 15px 30px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        nav a {
            color: white;
            text-decoration: none;
        }

        .nav-left {
            display: flex;
            gap: 20px;
            align-items: center;
        }

        .nav-right {
            display: flex;
            gap: 15px;
            align-items: center;
        }

        .container {
            width: 92%;
            max-width: 1200px;
            margin: 30px auto;
        }

        .card {
            background: white;
            border-radius: 8px;
            padding: 20px;
            margin-bottom: 20px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
        }

        input,
        textarea,
        select {
            width: 100%;
            padding: 10px;
            margin-top: 6px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        label {
            font-weight: bold;
        }

        button,
        .btn {
            display: inline-block;
            background: #0c66e4;
            color: white;
            border: none;
            padding: 10px 16px;
            border-radius: 5px;
            cursor: pointer;
            text-decoration: none;
        }

        button:hover,
        .btn:hover {
            opacity: 0.9;
        }

        .btn-secondary {
            background: #44546f;
        }

        .btn-danger {
            background: #c9372c;
        }

        .success {
            background: #d7f5df;
            color: #164b2b;
            padding: 12px;
            border-radius: 5px;
            margin-bottom: 20px;
        }

        .error {
            background: #ffe2e0;
            color: #ae2e24;
            padding: 12px;
            border-radius: 5px;
            margin-bottom: 20px;
        }

        .teams-grid {
            display: grid;
            grid-template-columns:
                repeat(auto-fill, minmax(260px, 1fr));
            gap: 20px;
        }

        .team-card {
            background: white;
            padding: 20px;
            border-radius: 8px;
            text-decoration: none;
            color: #172b4d;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
        }

        .team-card:hover {
            transform: translateY(-2px);
        }

        .board {
            display: grid;
            grid-template-columns:
                repeat(4, minmax(250px, 1fr));
            gap: 15px;
            overflow-x: auto;
            align-items: start;
        }

        .status-column {
            background: #ebecf0;
            border-radius: 8px;
            padding: 15px;
            min-height: 250px;
        }

        .task-card {
            background: white;
            border-radius: 6px;
            padding: 12px;
            margin-bottom: 10px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, .15);
        }

        .members {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
        }

        .member {
            background: #e9f2ff;
            padding: 8px 12px;
            border-radius: 20px;
        }

        .auth-box {
            max-width: 450px;
            margin: 60px auto;
        }
    </style>
</head>

<body>

@if(auth()->check())
    <nav>
        <div class="nav-left">
            <strong>TaskBoard</strong>

            <a href="{{ route('teams.index') }}">
                My Teams
            </a>
        </div>

        <div class="nav-right">

            <span>
                {{ auth()->user()->name }}
            </span>

            <form
                action="{{ route('logout') }}"
                method="POST"
                style="margin: 0;"
            >
                @csrf

                <button
                    type="submit"
                    class="btn-danger"
                >
                    Logout
                </button>
            </form>

        </div>
    </nav>
@endif


<div class="container">

    @if(session('success'))
        <div class="success">
            {{ session('success') }}
        </div>
    @endif


    @if($errors->any())
        <div class="error">
            <strong>Please fix the following errors:</strong>

            <ul>
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif


    @yield('content')

</div>

</body>
</html>