<?php

use App\Http\Controllers\ProjectController;
use App\Jobs\AwsS3\FetchUsEast1Status;
use App\Jobs\Digitalocean\FetchDropletsStatus;
use App\Jobs\Mailgun\FetchSmtpStatus;
use App\Mail\AgencyComponentStatusChanged;
use App\Models\Component;
use App\Models\Project;
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

Route::get('/agency-mail', function () {
    /** @var Project $project */
    $project = Project::query()->inRandomOrder()->first();

    /** @var Component $component */
    $component = $project->components()->first();

    return new AgencyComponentStatusChanged($project, $component);
});

Route::get('/update-status/aws-s3/us-east-1', function () {
    $component = Component::where('handle', 'aws-s3::us-east-1')->firstOrFail();
    FetchUsEast1Status::dispatchNow($component);
});

Route::get('/update-status/aws-s3/ap-south-1', function () {
    $component = Component::where('handle', 'aws-s3::ap-south-1')->firstOrFail();
    FetchUsEast1Status::dispatchNow($component);
});

Route::get('/update-status/digitalocean/droplets', function () {
    $component = Component::where('handle', 'digitalocean::droplets')->firstOrFail();
    FetchDropletsStatus::dispatchNow($component);
});

Route::get('/update-status/mailgun/smtp', function () {
    $component = Component::where('handle', 'mailgun::smtp')->firstOrFail();
    FetchSmtpStatus::dispatchNow($component);
});

Route::get('/update-status/sendgrid/parse-api', function () {
    $component = Component::where('handle', 'sendgrid::parse-api')->firstOrFail();
    FetchSmtpStatus::dispatchNow($component);
});

Route::middleware(['auth:sanctum', 'verified'])->group(function () {
    Route::get('/projects', fn () => redirect()->route('dashboard'));
    Route::get('/projects/new', [ProjectController::class, 'create'])->name('projects.create');
});

Route::middleware(['auth:sanctum', 'verified'])
    ->get('/dashboard', [ProjectController::class, 'index'])
    ->name('dashboard');
