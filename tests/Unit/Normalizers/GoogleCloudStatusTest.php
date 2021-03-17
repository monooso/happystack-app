<?php

namespace Tests\Unit\Normalizers;

use App\Constants\Status;
use App\Exceptions\UnknownStatusException;
use App\Normalizers\GoogleCloudStatus;
use PHPUnit\Framework\TestCase;

class GoogleCloudStatusTest extends TestCase
{
    /** @test */
    public function itNormalizesAClassStringContainingOk()
    {
        $this->assertSame(
            Status::OKAY,
            GoogleCloudStatus::normalize('end-bubble bubble ok')
        );
    }

    /** @test */
    public function itNormalizesAClassStringContainingMedium()
    {
        $this->assertSame(
            Status::WARN,
            GoogleCloudStatus::normalize('end-bubble bubble medium')
        );
    }

    /** @test */
    public function itNormalizesAClassStringContainingHigh()
    {
        $this->assertSame(
            Status::DOWN,
            GoogleCloudStatus::normalize('end-bubble bubble high')
        );
    }

    /** @test */
    public function itThrowsAnExceptionIfTheStatusIsUnknown()
    {
        $this->expectExceptionObject(new UnknownStatusException('nope'));

        GoogleCloudStatus::normalize('nope');
    }
}
