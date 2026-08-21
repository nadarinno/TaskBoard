<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\TeamController;
use Illuminate\Support\Facades\Route;


Route::get('/', function () {

    if (auth()->check()) {
        return redirect()
            ->route('teams.index');
    }

    return redirect()
        ->route('login');
});




Route::middleware('guest')->group(function () {

    Route::get(
        '/register',
        [AuthController::class, 'showRegister']
    )->name('register');

    Route::post(
        '/register',
        [AuthController::class, 'register']
    );


    Route::get(
        '/login',
        [AuthController::class, 'showLogin']
    )->name('login');

    Route::post(
        '/login',
        [AuthController::class, 'login']
    );
});




Route::middleware('auth')->group(function () {

   

    Route::post(
        '/logout',
        [AuthController::class, 'logout']
    )->name('logout');


    
    Route::get(
        '/teams',
        [TeamController::class, 'index']
    )->name('teams.index');


    Route::get(
        '/teams/create',
        [TeamController::class, 'create']
    )->name('teams.create');


    Route::post(
        '/teams',
        [TeamController::class, 'store']
    )->name('teams.store');


    Route::get(
        '/teams/{team}',
        [TeamController::class, 'show']
    )->name('teams.show');


    Route::post(
        '/teams/{team}/members',
        [TeamController::class, 'addMember']
    )->name('teams.members.add');


   

    Route::get(
        '/teams/{team}/tasks/create',
        [TaskController::class, 'create']
    )->name('tasks.create');


    Route::post(
        '/teams/{team}/tasks',
        [TaskController::class, 'store']
    )->name('tasks.store');


    Route::get(
        '/tasks/{task}',
        [TaskController::class, 'show']
    )->name('tasks.show');


    Route::get(
        '/tasks/{task}/edit',
        [TaskController::class, 'edit']
    )->name('tasks.edit');


    Route::put(
        '/tasks/{task}',
        [TaskController::class, 'update']
    )->name('tasks.update');


    Route::delete(
        '/tasks/{task}',
        [TaskController::class, 'destroy']
    )->name('tasks.destroy');


    Route::patch(
        '/tasks/{task}/move',
        [TaskController::class, 'move']
    )->name('tasks.move');


    Route::patch(
        '/tasks/{task}/close',
        [TaskController::class, 'close']
    )->name('tasks.close');
});