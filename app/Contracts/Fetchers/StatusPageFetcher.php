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
     * @param string $pageId
     *
     * @return ResponseInterface
     *
     * @throws GuzzleException
     */
    public function fetch(string $pageId): ResponseInterface;
}
