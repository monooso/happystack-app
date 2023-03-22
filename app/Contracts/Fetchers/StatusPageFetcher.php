<?php

declare(strict_types=1);

namespace App\Contracts\Fetchers;

use GuzzleHttp\Exception\GuzzleException;
use Psr\Http\Message\ResponseInterface;

interface StatusPageFetcher
{
    /**
     * Fetch the status
     *
     * @throws GuzzleException
     */
    public function fetch(string $pageId): ResponseInterface;
}
