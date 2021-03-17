<?php

declare(strict_types=1);

namespace App\Contracts\Parsers;

use App\Exceptions\UnknownComponentException;
use App\Exceptions\UnknownStatusException;
use Symfony\Component\DomCrawler\Crawler;

interface GoogleCloudParser
{
    /**
     * Extract the status of the specified component using the given crawler
     *
     * @param string  $componentId
     * @param Crawler $crawler
     *
     * @return string
     *
     * @throws UnknownComponentException
     * @throws UnknownStatusException
     */
    public function parse(string $componentId, Crawler $crawler): string;
}
