<?php

declare(strict_types=1);

namespace Tests\Feature\Jobs\Mailgun;

use App\Constants\Status;
use App\Events\StatusFetched;
use App\Jobs\Mailgun\FetchSmtpStatus;
use App\Models\Component;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;
use Tests\TestCase;

final class FetchSmtpStatusTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function itFetchesTheStatusAndRaisesAnEvent()
    {
        Event::fake();

        $component = Component::whereHandle('mailgun::smtp')->firstOrFail();

        FetchSmtpStatus::dispatchSync($component);

        Event::assertDispatched(function (StatusFetched $event) use ($component) {
            return $event->component->id === $component->id
                && in_array($event->status, Status::known());
        });
    }
}
