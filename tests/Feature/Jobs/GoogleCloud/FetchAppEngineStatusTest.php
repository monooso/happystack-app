<?php

declare(strict_types=1);

namespace Tests\Feature\Jobs\GoogleCloud;

use App\Constants\Status;
use App\Events\StatusFetched;
use App\Jobs\GoogleCloud\FetchAppEngineStatus;
use App\Models\Component;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;
use Tests\TestCase;

final class FetchAppEngineStatusTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function itFetchesTheStatusAndRaisesAnEvent()
    {
        $this->markTestIncomplete('@todo update Google status check code');

        Event::fake();

        $component = Component::query()
            ->where('handle', 'google-cloud::app-engine')
            ->firstOrFail();

        FetchAppEngineStatus::dispatchSync($component);

        Event::assertDispatched(function (StatusFetched $event) use ($component) {
            return $event->component->id === $component->id
                && in_array($event->status, Status::known());
        });
    }
}
