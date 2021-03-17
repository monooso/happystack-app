<?php

declare(strict_types=1);

namespace App\Fetchers;

use App\Contracts\Fetchers\GoogleCloudFetcher;
use Goutte\Client;
use Symfony\Component\DomCrawler\Crawler;

final class GoogleCloud implements GoogleCloudFetcher
{
    public function fetch(): Crawler
    {
        $url = 'https://status.cloud.google.com/';
        $client = new Client();

        return $client->request('GET', $url);
    }
}
