<?php

use App\Http\Controllers\ProjectController;
use App\Jobs\FetchManifestStatus;
use App\Jobs\UpdateMailgunStatus;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/update-status/mailgun', function () {
    UpdateMailgunStatus::dispatchNow();
});

Route::get('/update-status/manifest', function () {
    FetchManifestStatus::dispatchNow();
});

Route::middleware(['auth:sanctum', 'verified'])->group(function () {
    Route::get('/projects', [ProjectController::class, 'index'])->name('projects.index');
    Route::get('/projects/new', [ProjectController::class, 'create'])->name('projects.create');
});

Route::middleware(['auth:sanctum', 'verified'])->get('/dashboard', function () {
    return view('dashboard');
})->name('dashboard');
