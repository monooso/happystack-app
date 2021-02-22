<?php

declare(strict_types=1);

namespace App\Contracts\Fetchers;

use GuzzleHttp\Exception\GuzzleException;
use Psr\Http\Message\ResponseInterface;

interface AwsFetcher
{
    /**
     * Fetch the status
     *
     * @return ResponseInterface
     *
     * @throws GuzzleException
     */
    public function fetch(): ResponseInterface;
}
