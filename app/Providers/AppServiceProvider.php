<?php

namespace App\Providers;

use App\Actions\Projects\CreateProject;
use App\Contracts\CreatesProjects;
use App\Contracts\Fetchers\StatusPageFetcher as StatusPageFetcherContract;
use App\Contracts\Parsers\StatusPageParser as StatusPageParserContract;
use App\Fetchers\StatusPage as StatusPageFetcher;
use App\Parsers\StatusPage as StatusPageParser;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register()
    {
        $app = $this->app;

        $app->bind(CreatesProjects::class, CreateProject::class);

        $app->bind(StatusPageFetcherContract::class, StatusPageFetcher::class);

        $app->bind(StatusPageParserContract::class, StatusPageParser::class);
    }
}
