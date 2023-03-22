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
        $emails = ['john@doe.com', 'jane@doe.com'];

        // Configure the super user emails.
        config(['happystack.super_users' => $emails]);

        // Create the super users.
        $supers = collect($emails)->map(fn ($email) => User::factory()->make(['email' => $email]));

        $service = Service::factory()->make([
            'visibility' => ServiceVisibility::RESTRICTED,
        ]);

        foreach ($supers as $super) {
            $this->assertTrue((new ServicePolicy())->view($super, $service));
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
