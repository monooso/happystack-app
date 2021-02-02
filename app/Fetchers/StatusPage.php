<?php

declare(strict_types=1);

namespace App\Fetchers;

use GuzzleHttp\ClientInterface;
use GuzzleHttp\Exception\GuzzleException;
use Psr\Http\Message\ResponseInterface;

// @todo Extract interface
final class StatusPage
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

    /**
     * Fetch the status update
     *
     * @return ResponseInterface
     *
     * @throws GuzzleException
     */
    public function fetch(): ResponseInterface
    {
        $id = $this->pageId;

        // @todo Atlassian may also require a custom domain
        $url = "https://${id}.statuspage.io/api/v2/summary.json";

        return $this->client->request('GET', $url);
    }
}
