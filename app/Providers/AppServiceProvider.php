<?php

namespace App\Providers;

use App\Actions\Projects\CreateProject;
use App\Contracts\Fetchers\CreatesProjects;
use App\Contracts\Fetchers\StatusPageFetcher;
use App\Fetchers\StatusPage;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register()
    {
        $app = $this->app;

        $app->bind(CreatesProjects::class, CreateProject::class);

        $app->bind(StatusPageFetcher::class, StatusPage::class);
    }
}
