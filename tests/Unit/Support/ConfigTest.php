<?php

declare(strict_types=1);

namespace Tests\Unit\Support;

use App\Support\Config;
use PHPUnit\Framework\TestCase;

final class ConfigTest extends TestCase
{
    /** @test */
    public function stringToArrayConvertsADelimitedStringToAnArray()
    {
        $this->assertEquals(
            ['alpha', 'bravo', 'charlie'],
            Config::stringToArray('alpha|bravo|charlie')
        );
    }

    /** @test */
    public function stringToArrayRemovesWhitespaceFromEachItem()
    {
        $this->assertEquals(
            ['alpha', 'bravo', 'charlie'],
            Config::stringToArray('   alpha |       bravo  |charlie  ')
        );
    }
}
