<?php

namespace App\Providers;

use App\Actions\Projects\CreateProject;
use App\Contracts\CreatesProjects;
use App\Contracts\Fetchers\AwsFetcher as AwsFetcherContract;
use App\Contracts\Fetchers\StatusPageFetcher as StatusPageFetcherContract;
use App\Contracts\Parsers\AwsParser as AwsParserContract;
use App\Contracts\Parsers\StatusPageParser as StatusPageParserContract;
use App\Fetchers\Aws as AwsFetcher;
use App\Fetchers\StatusPage as StatusPageFetcher;
use App\Parsers\Aws as AwsParser;
use App\Parsers\StatusPage as StatusPageParser;
use GuzzleHttp\Client;
use GuzzleHttp\ClientInterface;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register()
    {
        $app = $this->app;

        $app->bind(ClientInterface::class, Client::class);

        $app->bind(CreatesProjects::class, CreateProject::class);

        $app->bind(AwsFetcherContract::class, AwsFetcher::class);
        $app->bind(AwsParserContract::class, AwsParser::class);

        $app->bind(StatusPageFetcherContract::class, StatusPageFetcher::class);
        $app->bind(StatusPageParserContract::class, StatusPageParser::class);
    }
}
