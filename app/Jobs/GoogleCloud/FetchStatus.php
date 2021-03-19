<?php

namespace App\Jobs\GoogleCloud;

use App\Contracts\Fetchers\GoogleCloudFetcher;
use App\Contracts\Parsers\GoogleCloudParser;
use App\Events\StatusFetched;
use App\Models\Component;
use Illuminate\Bus\Queueable;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\SerializesModels;

abstract class FetchStatus
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
     * @param GoogleCloudFetcher $fetcher
     * @param GoogleCloudParser  $parser
     */
    public function handle(GoogleCloudFetcher $fetcher, GoogleCloudParser $parser)
    {
        $crawler = $fetcher->fetch();

        $status = $parser->parse($this->getExternalComponentId(), $crawler);

        StatusFetched::dispatch($this->component, $status);
    }

    /**
     * Get the Google Cloud component ID
     *
     * @return string
     */
    abstract protected function getExternalComponentId(): string;
}
