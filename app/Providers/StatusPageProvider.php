<?php

namespace App\Providers;

use App\Fetchers\StatusPage;
use GuzzleHttp\Client;
use GuzzleHttp\ClientInterface;
use Illuminate\Support\ServiceProvider;

class StatusPageProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->when(StatusPage::class)->needs(ClientInterface::class)->give(Client::class);
    }
}
