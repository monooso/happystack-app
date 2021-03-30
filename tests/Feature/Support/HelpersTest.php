<?php

declare(strict_types=1);

namespace Tests\Feature\Support;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

final class HelpersTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function userReturnsTheActiveUser()
    {
        $user = User::factory()->create();

        $this->actingAs($user);

        $this->assertSame($user->id, user()->id);
    }

    /** @test */
    public function userReturnsNullIfThereIsNoActiveUser()
    {
        $this->assertNull(user());
    }
}
