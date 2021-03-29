<?php

use App\Http\Controllers\ProjectController;
use Illuminate\Support\Facades\Route;

// Restricted routes
Route::middleware(['auth:sanctum', 'subscribed', 'verified'])->group(function () {
    Route::resource('projects', ProjectController::class, [
        'only' => ['create', 'edit', 'index']
    ]);

    Route::redirect('/dashboard', route('projects.index'))->name('dashboard');
    Route::redirect('/', route('projects.index'))->name('home');
});

// Public routes
Route::middleware(['guest'])->group(function () {
    Route::redirect('/', 'login')->name('home');
});
