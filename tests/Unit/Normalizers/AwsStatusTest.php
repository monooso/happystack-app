<?php

namespace Tests\Unit\Normalizers;

use App\Constants\Status;
use App\Exceptions\UnknownStatusException;
use App\Normalizers\AwsStatus;
use PHPUnit\Framework\TestCase;

class AwsStatusTest extends TestCase
{
    /** @test */
    public function itNormalizesStatus1()
    {
        $this->assertSame(Status::OKAY, AwsStatus::normalize(1));
    }

    /** @test */
    public function itNormalizesStatus2()
    {
        $this->assertSame(Status::WARN, AwsStatus::normalize(2));
    }

    /** @test */
    public function itNormalizesStatus3()
    {
        $this->assertSame(Status::DOWN, AwsStatus::normalize(3));
    }

    /** @test */
    public function itThrowsAnExceptionIfTheStatusIsUnknown()
    {
        $this->expectExceptionObject(new UnknownStatusException('0'));

        AwsStatus::normalize(0);
    }
}
