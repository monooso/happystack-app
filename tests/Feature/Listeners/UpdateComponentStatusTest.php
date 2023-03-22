<?php

declare(strict_types=1);

namespace Tests\Feature\Listeners;

use App\Constants\Status;
use App\Events\StatusChanged;
use App\Events\StatusFetched;
use App\Listeners\UpdateComponentStatus;
use App\Models\Component;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Event;
use Tests\TestCase;

final class UpdateComponentStatusTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

    /** @test */
    public function itUpdatesTheComponentStatus()
    {
        Event::fake();

        $component = Component::factory()->create(['status' => Status::DOWN]);

        $event = new StatusFetched($component, Status::OKAY);

        (new UpdateComponentStatus())->handle($event);

        $this->assertDatabaseHas('components', [
            'id' => $component->id,
            'status' => Status::OKAY,
        ]);
    }

    /** @test */
    public function itUpdatesTheComponentStatusUpdatedAtTimestamp()
    {
        Event::fake();

        $this->travel($this->faker->randomNumber())->minutes();

        $component = Component::factory()->create([
            'status' => $this->faker->randomElement(Status::all()),
        ]);

        $event = new StatusFetched(
            $component,
            $this->faker->randomElement(Status::all())
        );

        (new UpdateComponentStatus())->handle($event);

        $this->assertDatabaseHas('components', [
            'id' => $component->id,
            'status_updated_at' => Carbon::now(),
        ]);

        $this->travelBack();
    }

    /** @test */
    public function itCreatesANewStatusHistoryRecord()
    {
        Event::fake();

        $this->travel($this->faker->randomNumber())->minutes();

        $component = Component::factory()->create(['status' => Status::DOWN]);

        $event = new StatusFetched($component, Status::OKAY);

        (new UpdateComponentStatus())->handle($event);

        $this->assertDatabaseHas('status_updates', [
            'component_id' => $component->id,
            'status' => Status::OKAY,
            'created_at' => Carbon::now(),
        ]);

        $this->travelBack();
    }

    /** @test */
    public function itDispatchesTheStatusUpdatedEventIfTheStatusHasChanged()
    {
        Event::fake();

        $component = Component::factory()->create(['status' => Status::WARN]);

        $event = new StatusFetched($component, Status::DOWN);

        (new UpdateComponentStatus())->handle($event);

        Event::assertDispatched(function (StatusChanged $event) use ($component) {
            return $event->component->id === $component->id;
        });
    }

    /** @test */
    public function itDoesNotDispatchTheStatusUpdatedEventIfTheStatusHasNotChanged()
    {
        Event::fake();

        $component = Component::factory()->create(['status' => Status::OKAY]);

        $event = new StatusFetched($component, Status::OKAY);

        (new UpdateComponentStatus())->handle($event);

        Event::assertNotDispatched(StatusChanged::class);
    }
}
