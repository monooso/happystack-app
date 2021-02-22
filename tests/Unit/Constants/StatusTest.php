<?php

declare(strict_types=1);

namespace Tests\Unit\Constants;

use App\Constants\Status;
use PHPUnit\Framework\TestCase;

final class StatusTest extends TestCase
{
    /** @test */
    public function allReturnsAnArrayOfEveryStatusConstant()
    {
        $this->assertEquals(
            [Status::OKAY, Status::WARN, Status::DOWN, Status::UNKNOWN],
            Status::all()
        );
    }

    /** @test */
    public function knownReturnsAnArrayOfEveryStatusExceptUnknown()
    {
        $this->assertEquals(
            [Status::OKAY, Status::WARN, Status::DOWN],
            Status::known()
        );
    }
}
