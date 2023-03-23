<?php

use App\Jobs\Arcustech\FetchV2PlatformEuNlStatus;
use App\Jobs\AwsS3\FetchUsEast1Status;
use App\Jobs\Digitalocean\FetchDropletsStatus;
use App\Jobs\GoogleCloud\FetchComputeEngineStatus;
use App\Jobs\Mailgun\FetchSmtpStatus;
use App\Jobs\Sendgrid\FetchParseApiStatus;
use App\Mail\AgencyComponentStatusChanged;
use App\Mail\ClientComponentStatusChanged;
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

Route::get('client-mail', function () {
    $message = <<<'MESSAGE'
Hi Jimbob,

The world is on fire. This is fine.

Regards,
Agency
MESSAGE;

    return new ClientComponentStatusChanged($message);
});

Route::get('update-status/arcustech/platform', function () {
    $component = Component::where('handle', 'arcustech::v2-platform-eu-nl')->firstOrFail();
    FetchV2PlatformEuNlStatus::dispatchSync($component);
});

Route::get('update-status/aws-s3/us-east-1', function () {
    $component = Component::where('handle', 'aws-s3::us-east-1')->firstOrFail();
    FetchUsEast1Status::dispatchSync($component);
});

Route::get('update-status/aws-s3/ap-south-1', function () {
    $component = Component::where('handle', 'aws-s3::ap-south-1')->firstOrFail();
    FetchUsEast1Status::dispatchSync($component);
});

Route::get('update-status/digitalocean/droplets', function () {
    $component = Component::where('handle', 'digitalocean::droplets')->firstOrFail();
    FetchDropletsStatus::dispatchSync($component);
});

Route::get('update-status/google-cloud/compute-engine', function () {
    $component = Component::where('handle', 'google-cloud::compute-engine')->firstOrFail();
    FetchComputeEngineStatus::dispatchSync($component);
});

Route::get('update-status/mailgun/smtp', function () {
    $component = Component::where('handle', 'mailgun::smtp')->firstOrFail();
    FetchSmtpStatus::dispatchSync($component);
});

Route::get('update-status/sendgrid/parse-api', function () {
    $component = Component::where('handle', 'sendgrid::parse-api')->firstOrFail();
    FetchParseApiStatus::dispatchSync($component);
});
