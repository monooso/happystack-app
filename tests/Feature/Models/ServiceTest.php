<?php

declare(strict_types=1);

namespace Tests\Feature\Models;

use App\Constants\ServiceVisibility;
use App\Models\Service;
use Tests\TestCase;

final class ServiceTest extends TestCase
{
    /** @test */
    public function isRestrictedReturnsTrueIfTheVisibilityIsRestricted()
    {
        $service = Service::factory()->make([
            'visibility' => ServiceVisibility::RESTRICTED,
        ]);

        $this->assertTrue($service->isRestricted());
    }

    /** @test */
    public function isRestrictedReturnsFalseIfTheVisibilityIsPublic()
    {
        $service = Service::factory()->make([
            'visibility' => ServiceVisibility::PUBLIC,
        ]);

        $this->assertFalse($service->isRestricted());
    }
}
