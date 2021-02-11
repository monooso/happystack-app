<?php

use App\Jobs\UpdateMailgunStatus;
use App\Jobs\UpdateManifestStatus;
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
    UpdateManifestStatus::dispatchNow();
});
Route::middleware(['auth:sanctum', 'verified'])->get('/dashboard', function () {
    return view('dashboard');
})->name('dashboard');
