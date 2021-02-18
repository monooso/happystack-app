<?php

namespace Tests\Unit\Normalizers;

use App\Constants\Status;
use App\Normalizers\StatusPageStatus;
use PHPUnit\Framework\TestCase;

class StatusPageStatusTest extends TestCase
{
    /** @test */
    public function itNormalizesTheOperationalStatus()
    {
        $this->assertSame(Status::OKAY, StatusPageStatus::normalize('operational'));
    }

    /** @test */
    public function itNormalizesTheDegradedPerformanceStatus()
    {
        $this->assertSame(Status::WARN, StatusPageStatus::normalize('degraded_performance'));
    }

    /** @test */
    public function itNormalizesThePartialOutageStatus()
    {
        $this->assertSame(Status::WARN, StatusPageStatus::normalize('partial_outage'));
    }

    /** @test */
    public function itNormalizesTheMajorOutageStatus()
    {
        $this->assertSame(Status::DOWN, StatusPageStatus::normalize('major_outage'));
    }

    /** @test */
    public function itFallsBackToUnknown()
    {
        $this->assertSame(Status::UNKNOWN, StatusPageStatus::normalize('wibble'));
    }
}
