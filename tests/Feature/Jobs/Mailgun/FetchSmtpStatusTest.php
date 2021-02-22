<?php

declare(strict_types=1);

namespace Tests\Feature\Jobs\Mailgun;

use App\Constants\Status;
use App\Events\StatusRetrieved;
use App\Jobs\Mailgun\FetchSmtpStatus;
use App\Models\Service;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Event;
use Tests\TestCase;

final class FetchSmtpStatusTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

    /** @test */
    public function itFetchesTheStatusAndRaisesAnEvent()
    {
        Event::fake();

        $service = Service::whereHandle('mailgun')->firstOrFail();
        $component = $service->components()->whereHandle('smtp')->firstOrFail();

        FetchSmtpStatus::dispatchNow($component);

        Event::assertDispatched(function (StatusRetrieved $event) use ($component) {
            return $event->component->id === $component->id
                && $event->status === Status::OKAY;
        });
    }
}
