<?php

declare(strict_types=1);

namespace Tests\Feature\Jobs\AwsS3;

use App\Constants\Status;
use App\Events\StatusRetrieved;
use App\Jobs\AwsS3\FetchUsStandardStatus;
use App\Models\Component;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;
use Tests\TestCase;

final class FetchUsStandardStatusTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function itFetchesTheStatusAndRaisesAnEvent()
    {
        Event::fake();

        $component = Component::whereHandle('s3-us-standard')->firstOrFail();

        FetchUsStandardStatus::dispatchNow($component);

        Event::assertDispatched(function (StatusRetrieved $event) use ($component) {
            return $event->component->id === $component->id
                && in_array($event->status, Status::known());
        });
    }
}
