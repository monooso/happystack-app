<?php

declare(strict_types=1);

namespace Tests\Unit\Helpers;

use App\Helpers\ArrayHelper;
use PHPUnit\Framework\TestCase;

final class ArrayHelperTest extends TestCase
{
    /** @test */
    public function flattenAssocFlattensAnAssociativeArray()
    {
        $input = ['name' => ['required' => 'The name is required']];
        $expected = ['name.required' => 'The name is required'];

        $this->assertSame($expected, ArrayHelper::flattenAssoc($input));
    }

    /** @test */
    public function flattenAssocFlattensADeeplyNestedAssociativeArray()
    {
        $input = [
            'regions' => [
                'europe' => [
                    'countries' => [
                        'france' => 'fr',
                        'spain'  => 'es',
                    ],
                ],
            ],
        ];

        $expected = [
            'regions.europe.countries.france' => 'fr',
            'regions.europe.countries.spain'  => 'es',
        ];

        $this->assertSame($expected, ArrayHelper::flattenAssoc($input));
    }

    /** @test */
    public function flattenAssocDoesNotProcessNonAssociativeArrays()
    {
        $input = ['alfa', 'bravo', 'charlie'];

        $this->assertSame($input, ArrayHelper::flattenAssoc($input));
    }

    /** @test */
    public function flattenAssocDoesNotProcessNestedNonAssociativeArrays()
    {
        $input = ['regions' => ['europe' => ['France', 'Germany']]];
        $expected = ['regions.europe' => ['France', 'Germany']];

        $this->assertSame($expected, ArrayHelper::flattenAssoc($input));
    }
}
