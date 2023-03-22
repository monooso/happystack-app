<?php

use App\Http\Controllers\CreateFirstTeamController;
use App\Http\Controllers\JoinTeamController;
use App\Http\Controllers\ProjectController;
use Illuminate\Support\Facades\Route;

// Application routes
Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
    'has-team',
])->group(function () {
    Route::resource('projects', ProjectController::class, ['only' => ['create', 'edit', 'index']]);
    Route::redirect('/dashboard', route('projects.index'))->name('dashboard');
    Route::redirect('/', route('projects.index'))->name('home');
});

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
    'missing-team',
])->group(function () {
    Route::get('/create-first-team', CreateFirstTeamController::class)->name('teams.create-first');
});

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/join-team', JoinTeamController::class)->name('teams.join');
});

// Public routes
Route::middleware(['guest'])->group(function () {
    Route::redirect('/', 'login')->name('home');
});
