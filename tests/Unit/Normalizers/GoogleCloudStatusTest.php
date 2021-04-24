<?php

namespace Tests\Unit\Normalizers;

use App\Constants\Status;
use App\Exceptions\UnknownStatusException;
use App\Normalizers\GoogleCloudStatus;
use PHPUnit\Framework\TestCase;

class GoogleCloudStatusTest extends TestCase
{
    /** @test */
    public function itNormalizesAClassStringContainingAvailable()
    {
        $this->assertSame(
            Status::OKAY,
            GoogleCloudStatus::normalize('lorem-ipsum dolor available')
        );
    }

    /** @test */
    public function itNormalizesAClassStringContainingInformation()
    {
        $this->assertSame(
            Status::OKAY,
            GoogleCloudStatus::normalize('lorem-ipsum information dolor')
        );
    }

    /** @test */
    public function itNormalizesAClassStringContainingDisruption()
    {
        $this->assertSame(
            Status::WARN,
            GoogleCloudStatus::normalize('dolor disruption lorem-ipsum')
        );
    }

    /** @test */
    public function itNormalizesAClassStringContainingOutage()
    {
        $this->assertSame(
            Status::DOWN,
            GoogleCloudStatus::normalize('lorem ipsum outage dolor sit-amet')
        );
    }

    /** @test */
    public function itThrowsAnExceptionIfTheStatusIsUnknown()
    {
        $this->expectExceptionObject(new UnknownStatusException('nope'));

        GoogleCloudStatus::normalize('nope');
    }
}
