<?php

declare(strict_types=1);

namespace App\Fetchers;

use App\Contracts\Fetchers\StatusPageFetcher;
use GuzzleHttp\ClientInterface;
use Psr\Http\Message\ResponseInterface;

final class StatusPage implements StatusPageFetcher
{
    public function __construct(private ClientInterface $client)
    {
    }

    public function fetch(string $pageId): ResponseInterface
    {
        $url = "https://${pageId}.statuspage.io/api/v2/summary.json";

        return $this->client->request('GET', $url);
    }
}
