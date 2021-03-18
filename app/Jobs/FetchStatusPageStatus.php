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

    /** @var Component */
    public Component $component;

    /**
     * Constructor
     *
     * @param Component $component
     */
    public function __construct(Component $component)
    {
        $this->component = $component;
    }

    /**
     * Execute the job
     *
     * @param StatusPageFetcher $fetcher
     * @param StatusPageParser  $parser
     *
     * @throws GuzzleException
     */
    public function handle(StatusPageFetcher $fetcher, StatusPageParser $parser)
    {
        $response = $fetcher->fetch($this->getPageId());

        $status = $parser->parse($this->getComponentId(), $response);

        StatusFetched::dispatch($this->component, $status);
    }

    /**
     * Get the StatusPage component ID
     *
     * @return string
     */
    abstract protected function getComponentId(): string;

    /**
     * Get the StatusPage page ID
     *
     * @return string
     */
    abstract protected function getPageId(): string;
}
