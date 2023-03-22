<?php

namespace App\Jobs;

use App\Contracts\Fetchers\StatusPageFetcher;
use App\Contracts\Parsers\StatusPageParser;
use App\Events\StatusFetched;
use App\Models\Component;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Bus\Queueable;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\SerializesModels;

abstract class FetchStatusPageStatus
{
    use Dispatchable;
    use Queueable;
    use SerializesModels;

    public function __construct(public Component $component)
    {
    }

    /**
     * Execute the job
     *
     * @throws GuzzleException
     */
    public function handle(StatusPageFetcher $fetcher, StatusPageParser $parser)
    {
        $response = $fetcher->fetch($this->getExternalPageId());

        $status = $parser->parse($this->getExternalComponentId(), $response);

        StatusFetched::dispatch($this->component, $status);
    }

    /**
     * Get the StatusPage component ID
     */
    abstract protected function getExternalComponentId(): string;

    /**
     * Get the StatusPage page ID
     */
    abstract protected function getExternalPageId(): string;
}
