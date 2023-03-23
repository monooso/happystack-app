<?php

declare(strict_types=1);

namespace Tests\Feature\Models;

use App\Constants\Status;
use App\Models\Component;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Config;
use Tests\TestCase;

final class ComponentTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

    /** @test */
    public function scopeDownRestrictsResultsToComponentsThatAreDown()
    {
        $down = Component::first();
        $down->status = Status::DOWN;
        $down->save();

        $result = Component::down()->get();

        $this->assertCount(1, $result);
        $this->assertSame($down->id, $result->first()->id);
    }

    /** @test */
    public function scopeWarnRestrictsResultsToComponentsThatHaveAWarning()
    {
        $down = Component::first();
        $down->status = Status::WARN;
        $down->save();

        $result = Component::warn()->get();

        $this->assertCount(1, $result);
        $this->assertSame($down->id, $result->first()->id);
    }

    /** @test */
    public function scopeStaleOmitsRecentlyUpdatedComponents()
    {
        $refreshInterval = $this->faker->numberBetween(60, 600);

        Config::set('happystack.status_refresh_interval', $refreshInterval);

        $stale = Component::first();
        $stale->updated_at = Carbon::now()->subSeconds($refreshInterval + 1);
        $stale->save();

        $result = Component::stale()->get();

        $this->assertCount(1, $result);
        $this->assertSame($stale->id, $result->first()->id);
    }

    /** @test */
    public function updateStatusUpdatesTheComponentStatus()
    {
        $component = Component::factory()->create(['status' => Status::DOWN]);

        $component->updateStatus(Status::OKAY);

        $this->assertDatabaseHas('components', [
            'id' => $component->id,
            'status' => Status::OKAY,
        ]);
    }

    /** @test */
    public function updateStatusUpdatesTheStatusUpdatedAtTimestamp()
    {
        $this->travel($this->faker->randomNumber())->minutes();

        $component = Component::factory()->create([
            'status_updated_at' => Carbon::now()->subDays(2),
        ]);

        $component->updateStatus($this->faker->randomElement(Status::all()));

        $this->assertDatabaseHas('components', [
            'id' => $component->id,
            'status_updated_at' => Carbon::now(),
        ]);

        $this->travelBack();
    }

    /** @test */
    public function updateStatusCreatesANewStatusUpdateRecord()
    {
        $this->travel($this->faker->randomNumber())->minutes();

        $status = $this->faker->randomElement(Status::all());
        $component = Component::factory()->create();

        $component->updateStatus($status);

        $this->assertDatabaseHas('status_updates', [
            'component_id' => $component->id,
            'status' => $status,
            'updated_at' => Carbon::now(),
        ]);

        $this->travelBack();
    }
}
