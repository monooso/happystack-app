<?php

declare(strict_types=1);

namespace App\Fetchers;

use App\Contracts\Fetchers\StatusPageFetcher;
use GuzzleHttp\ClientInterface;
use Psr\Http\Message\ResponseInterface;

final class StatusPage implements StatusPageFetcher
{
    private ClientInterface $client;

    private string $pageId;

    /**
     * Constructor
     *
     * @param ClientInterface $client
     * @param string          $pageId
     */
    public function __construct(ClientInterface $client, string $pageId)
    {
        $this->client = $client;
        $this->pageId = $pageId;
    }

    public function fetch(): ResponseInterface
    {
        $id = $this->pageId;

        $url = "https://${id}.statuspage.io/api/v2/summary.json";

        return $this->client->request('GET', $url);
    }
}
