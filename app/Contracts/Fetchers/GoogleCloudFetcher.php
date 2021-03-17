<?php

declare(strict_types=1);

namespace App\Contracts\Fetchers;

use Symfony\Component\DomCrawler\Crawler;

interface GoogleCloudFetcher
{
    /**
     * Fetch the status
     *
     * @return Crawler
     */
    public function fetch(): Crawler;
}
