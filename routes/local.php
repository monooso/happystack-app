<?php

use App\Jobs\Arcustech\FetchV2PlatformEuNlStatus;
use App\Jobs\AwsS3\FetchUsEast1Status;
use App\Jobs\Digitalocean\FetchDropletsStatus;
use App\Jobs\GoogleCloud\FetchComputeEngineStatus;
use App\Jobs\Mailgun\FetchSmtpStatus;
use App\Jobs\Sendgrid\FetchParseApiStatus;
use App\Mail\AgencyComponentStatusChanged;
use App\Models\Component;
use App\Models\Project;
use Illuminate\Support\Facades\Route;

Route::get('agency-mail', function () {
    /** @var Project $project */
    $project = Project::query()->inRandomOrder()->first();

    /** @var Component $component */
    $component = $project->components()->first();

    return new AgencyComponentStatusChanged($project, $component);
});

Route::get('update-status/arcustech/platform', function () {
    $component = Component::where('handle', 'arcustech::v2-platform-eu-nl')->firstOrFail();
    FetchV2PlatformEuNlStatus::dispatchNow($component);
});

Route::get('update-status/aws-s3/us-east-1', function () {
    $component = Component::where('handle', 'aws-s3::us-east-1')->firstOrFail();
    FetchUsEast1Status::dispatchNow($component);
});

Route::get('update-status/aws-s3/ap-south-1', function () {
    $component = Component::where('handle', 'aws-s3::ap-south-1')->firstOrFail();
    FetchUsEast1Status::dispatchNow($component);
});

Route::get('update-status/digitalocean/droplets', function () {
    $component = Component::where('handle', 'digitalocean::droplets')->firstOrFail();
    FetchDropletsStatus::dispatchNow($component);
});

Route::get('update-status/google-cloud/compute-engine', function () {
    $component = Component::where('handle', 'google-cloud::compute-engine')->firstOrFail();
    FetchComputeEngineStatus::dispatchNow($component);
});

Route::get('update-status/mailgun/smtp', function () {
    $component = Component::where('handle', 'mailgun::smtp')->firstOrFail();
    FetchSmtpStatus::dispatchNow($component);
});

Route::get('update-status/sendgrid/parse-api', function () {
    $component = Component::where('handle', 'sendgrid::parse-api')->firstOrFail();
    FetchParseApiStatus::dispatchNow($component);
});
