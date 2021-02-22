<?php

declare(strict_types=1);

namespace App\Fetchers;

use App\Contracts\Fetchers\AwsFetcher;
use GuzzleHttp\ClientInterface;
use Psr\Http\Message\ResponseInterface;

final class Aws implements AwsFetcher
{
    private ClientInterface $client;

    /**
     * Constructor
     *
     * @param ClientInterface $client
     */
    public function __construct(ClientInterface $client)
    {
        $this->client = $client;
    }

    public function fetch(): ResponseInterface
    {
        $url = "https://status.aws.amazon.com/data.json";

        return $this->client->request('GET', $url);
    }
}
