<?php

use App\Http\Controllers\ProjectController;
use App\Jobs\AwsS3\FetchUsEast1Status;
use App\Jobs\Mailgun\FetchSmtpStatus;
use App\Models\Component;
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

Route::get('/update-status/aws-s3/us-standard', function () {
    $component = Component::where('handle', 'aws-s3::us-standard')->firstOrFail();
    FetchUsEast1Status::dispatchNow($component);
});

Route::get('/update-status/aws-s3/ap-south-1', function () {
    $component = Component::where('handle', 'aws-s3::ap-south-1')->firstOrFail();
    FetchUsEast1Status::dispatchNow($component);
});

Route::get('/update-status/mailgun/smtp', function () {
    $component = Component::where('handle', 'mailgun::smtp')->firstOrFail();
    FetchSmtpStatus::dispatchNow($component);
});

Route::middleware(['auth:sanctum', 'verified'])->group(function () {
    Route::get('/projects', [ProjectController::class, 'index'])->name('projects.index');
    Route::get('/projects/new', [ProjectController::class, 'create'])->name('projects.create');
});

Route::middleware(['auth:sanctum', 'verified'])->get('/dashboard', function () {
    return view('dashboard');
})->name('dashboard');
