<?php

namespace App\Jobs;

use App\Events\StatusRetrieved;
use App\Fetchers\StatusPage as Fetcher;
use App\Parsers\StatusPage as Parser;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\App;

abstract class UpdateStatusPageStatus implements ShouldQueue, ShouldBeUnique
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    /**
     * Execute the job.
     *
     * @return void
     * @throws BindingResolutionException
     */
    public function handle(): void
    {
        $fetcher = App::makeWith(Fetcher::class, ['pageId' => $this->getPageId()]);
        $parser  = App::makeWith(Parser::class, ['serviceKey' => $this->getServiceKey()]);

        $response   = $fetcher->fetch();
        $components = $parser->parse($response);

        foreach ($components as $component) {
            StatusRetrieved::dispatch($component);
        }
    }

    /**
     * Get the StatusPage pageId
     *
     * @return string
     */
    abstract protected function getPageId(): string;

    /**
     * Get the interval service key
     *
     * For example, 'mailgun'
     *
     * @return string
     */
    abstract protected function getServiceKey(): string;
}
