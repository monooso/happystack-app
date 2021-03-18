<?php

declare(strict_types=1);

namespace Tests\Feature\Jobs\AwsS3;

use App\Constants\Status;
use App\Events\StatusFetched;
use App\Jobs\AwsS3\FetchUsEast1Status;
use App\Models\Component;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;
use Tests\TestCase;

final class FetchUsEast1StatusTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function itFetchesTheStatusAndRaisesAnEvent()
    {
        Event::fake();

        $component = Component::whereHandle('aws-s3::us-east-1')->firstOrFail();

        FetchUsEast1Status::dispatchNow($component);

        Event::assertDispatched(function (StatusFetched $event) use ($component) {
            return $event->component->id === $component->id
                && in_array($event->status, Status::known());
        });
    }
}
