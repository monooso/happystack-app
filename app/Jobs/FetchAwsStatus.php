<?php

namespace App\Jobs;

use App\Contracts\Fetchers\AwsFetcher;
use App\Contracts\Parsers\AwsParser;
use App\Events\StatusFetched;
use App\Models\Component;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Bus\Queueable;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\SerializesModels;

abstract class FetchAwsStatus
{
    use Dispatchable;
    use Queueable;
    use SerializesModels;

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
     * @param AwsFetcher $fetcher
     * @param AwsParser  $parser
     *
     * @throws GuzzleException
     */
    public function handle(AwsFetcher $fetcher, AwsParser $parser)
    {
        $response = $fetcher->fetch();

        $status = $parser->parse($this->getExternalComponentId(), $response);

        StatusFetched::dispatch($this->component, $status);
    }

    /**
     * Get the AWS component ID
     *
     * @return string
     */
    abstract protected function getExternalComponentId(): string;
}
