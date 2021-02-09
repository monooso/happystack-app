<?php

declare(strict_types=1);

namespace App\Contracts;

use GuzzleHttp\Exception\GuzzleException;
use Psr\Http\Message\ResponseInterface;

interface StatusFetcher
{
    /**
     * Fetch the status update
     *
     * @return ResponseInterface
     *
     * @throws GuzzleException
     */
    public function fetch(): ResponseInterface;
}
