<?php

declare(strict_types=1);

namespace Tests\Feature\Listeners;

use App\Constants\Status;
use App\Events\StatusRetrieved;
use App\Events\StatusUpdated;
use App\Listeners\UpdateComponentStatus;
use App\Models\Component;
use App\Models\StatusUpdate;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Event;
use Tests\TestCase;

final class UpdateComponentStatusTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function itUpdatesTheComponentStatusIfTheStatusHasChanged()
    {
        Event::fake();

        $component = Component::factory()->forService()->create(['current_status' => Status::DOWN]);

        $event = new StatusRetrieved($component, Status::OKAY);

        (new UpdateComponentStatus())->handle($event);

        $this->assertDatabaseHas('components', [
            'id'             => $component->id,
            'current_status' => Status::OKAY,
        ]);
    }

    /** @test */
    public function itDispatchesTheStatusUpdatedEventIfTheStatusHasChanged()
    {
        Event::fake();

        $component = Component::factory()->forService()->create(['current_status' => Status::DOWN]);

        $event = new StatusRetrieved($component, Status::OKAY);

        (new UpdateComponentStatus())->handle($event);

        Event::assertDispatched(function (StatusUpdated $event) use ($component) {
            return $event->component->id === $component->id;
        });
    }

    /** @test */
    public function itTouchesTheMostRecentStatusUpdateIfTheStatusHasNotChanged()
    {
        Event::fake();

        $this->travel(-5)->hours();
        $lastUpdated = Carbon::now()->subWeeks(3);

        $component = Component::factory()->forService()->create(['current_status' => Status::OKAY]);

        $statusUpdate = StatusUpdate::factory()->for($component)->create([
            'status'     => Status::OKAY,
            'created_at' => $lastUpdated,
            'updated_at' => $lastUpdated,
        ]);

        $event = new StatusRetrieved($component, Status::OKAY);

        (new UpdateComponentStatus())->handle($event);

        $this->assertDatabaseHas('status_updates', [
            'id'         => $statusUpdate->id,
            'updated_at' => Carbon::now(),
        ]);
    }

    /** @test */
    public function itDoesNotDispatchAnEventIfTheStatusHasNotChanged()
    {
        Event::fake();

        $component = Component::factory()->forService()->create(['current_status' => Status::OKAY]);
        StatusUpdate::factory()->for($component)->create(['status' => Status::OKAY]);

        $event = new StatusRetrieved($component, Status::OKAY);

        (new UpdateComponentStatus())->handle($event);

        Event::assertNotDispatched(StatusUpdated::class);
    }
}
