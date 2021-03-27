<?php

use App\Http\Controllers\ProjectController;
use Illuminate\Support\Facades\Route;

// Restricted routes
Route::middleware(['auth:sanctum', 'verified'])->group(function () {
    Route::resource('projects', ProjectController::class, [
        'only' => ['create', 'edit', 'index']
    ]);

    Route::redirect('/dashboard', route('projects.index'))->name('dashboard');
});

// Public routes (the projects.index route must be defined first)
Route::redirect('/', route('projects.index'));
