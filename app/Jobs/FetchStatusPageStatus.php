<?php

namespace App\Jobs;

use App\Contracts\Fetchers\StatusPageFetcher;
use App\Events\StatusRetrieved;
use App\Models\Component;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

abstract class FetchStatusPageStatus implements ShouldQueue, ShouldBeUnique
{
    use Dispatchable;
    use InteractsWithQueue;
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
     * Execute the job.
     *
     * @param StatusPageFetcher $fetcher
     *
     * @throws BindingResolutionException
     */
    public function handle(StatusPageFetcher $fetcher)
    {
        $response = $fetcher->fetch($this->getPageId());

        $response = $fetcher->fetch();
        $components = $parser->parse($response);

        foreach ($components as $component) {
            StatusRetrieved::dispatch($component);
        }
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
