<?php

declare(strict_types=1);

namespace Tests\Feature\Policies;

use App\Constants\ServiceVisibility;
use App\Models\Service;
use App\Models\User;
use App\Policies\ServicePolicy;
use Tests\TestCase;

final class ServicePolicyTest extends TestCase
{
    /** @test */
    public function viewReturnsTrueIfTheServiceIsNotRestricted()
    {
        $user = User::factory()->make();

        $service = Service::factory()->make([
            'visibility' => ServiceVisibility::PUBLIC,
        ]);

        $this->assertTrue((new ServicePolicy())->view($user, $service));
    }

    /** @test */
    public function viewReturnsTrueIfTheServiceIsRestrictedAndTheUserIsAGod()
    {
        $gods = [
            User::factory()->make(['email' => 'stephen@happystack.app']),
            User::factory()->make(['email' => 'stephen@manifest.uk.com']),
        ];

        $service = Service::factory()->make([
            'visibility' => ServiceVisibility::RESTRICTED,
        ]);

        foreach ($gods as $god) {
            $this->assertTrue((new ServicePolicy())->view($god, $service));
        }
    }

    /** @test */
    public function viewReturnsFalseIfTheServiceIsRestrictedAndTheUserIsNotAGod()
    {
        $user = User::factory()->make();

        $service = Service::factory()->make([
            'visibility' => ServiceVisibility::RESTRICTED,
        ]);

        $this->assertFalse((new ServicePolicy())->view($user, $service));
    }
}
