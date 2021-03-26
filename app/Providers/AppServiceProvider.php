<?php

namespace App\Providers;

use App\Actions\Projects\CreateProject;
use App\Actions\Projects\DeleteProject;
use App\Actions\Projects\UpdateProject;
use App\Contracts\CreatesProjects;
use App\Contracts\DeletesProjects;
use App\Contracts\Fetchers\AwsFetcher as AwsFetcherContract;
use App\Contracts\Fetchers\GoogleCloudFetcher as GoogleCloudFetcherContract;
use App\Contracts\Fetchers\StatusPageFetcher as StatusPageFetcherContract;
use App\Contracts\Parsers\AwsParser as AwsParserContract;
use App\Contracts\Parsers\GoogleCloudParser as GoogleCloudParserContract;
use App\Contracts\Parsers\StatusPageParser as StatusPageParserContract;
use App\Contracts\UpdatesProjects;
use App\Fetchers\Aws as AwsFetcher;
use App\Fetchers\GoogleCloud as GoogleCloudFetcher;
use App\Fetchers\StatusPage as StatusPageFetcher;
use App\Parsers\Aws as AwsParser;
use App\Parsers\GoogleCloud as GoogleCloudParser;
use App\Parsers\StatusPage as StatusPageParser;
use GuzzleHttp\Client;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\HandlerStack;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\ServiceProvider;
use Kevinrob\GuzzleCache\CacheMiddleware;
use Kevinrob\GuzzleCache\Storage\LaravelCacheStorage;
use Kevinrob\GuzzleCache\Strategy\GreedyCacheStrategy;

class AppServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->registerHttpClient();
        $this->registerFetchers();
        $this->registerParsers();
        $this->registerActions();
    }

    /**
     * Register the Guzzle ClientInterface implementation
     *
     * Cache all API results for 4 minutes, regardless of what the response
     * headers say. This is mostly because the AWS data is not a proper
     * API endpoint, and behaves poorly.
     */
    private function registerHttpClient()
    {
        $this->app->singleton(ClientInterface::class, function () {
            $cacheStorage = new LaravelCacheStorage(Cache::store());

            $cacheMiddleware = new CacheMiddleware(new GreedyCacheStrategy($cacheStorage, 240));

            $stack = HandlerStack::create();
            $stack->push($cacheMiddleware, 'cache');

            return new Client(['handler' => $stack]);
        });
    }

    /**
     * Register component status fetchers with the container
     */
    private function registerFetchers()
    {
        $app = $this->app;

        $app->bind(AwsFetcherContract::class, AwsFetcher::class);
        $app->bind(GoogleCloudFetcherContract::class, GoogleCloudFetcher::class);
        $app->bind(StatusPageFetcherContract::class, StatusPageFetcher::class);
    }

    /**
     * Register component status parsers with the container
     */
    private function registerParsers()
    {
        $app = $this->app;

        $app->bind(AwsParserContract::class, AwsParser::class);
        $app->bind(GoogleCloudParserContract::class, GoogleCloudParser::class);
        $app->bind(StatusPageParserContract::class, StatusPageParser::class);
    }

    /**
     * Register actions with the container
     */
    private function registerActions()
    {
        $this->app->bind(CreatesProjects::class, CreateProject::class);
        $this->app->bind(DeletesProjects::class, DeleteProject::class);
        $this->app->bind(UpdatesProjects::class, UpdateProject::class);
    }
}
