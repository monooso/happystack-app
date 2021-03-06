<?php

declare(strict_types=1);

namespace Tests\Feature\Actions\Components;

use App\Actions\Components\RefreshStatus;
use App\Jobs\AwsS3\FetchUsEast1Status;
use Tests\TestCase;

final class RefreshStatusTest extends TestCase
{
    /** @test */
    public function resolveJobClassReturnsAFullyQualifiedClassName()
    {
        $expected = '\\' . FetchUsEast1Status::class;
        $actual = (new RefreshStatus())->resolveJobClass('aws-s3::us-east-1');

        $this->assertSame($expected, $actual);
    }

    /** @test */
    public function resolveJobClassReturnsNullIfTheHandleIsMalformed()
    {
        $this->assertNull((new RefreshStatus())->resolveJobClass('nope'));
    }

    /** @test */
    public function resolveJobClassReturnsNullIfTheHandleServiceIsEmpty()
    {
        $this->assertNull((new RefreshStatus())->resolveJobClass(' ::us-east-1'));
    }

    /** @test */
    public function resolveJobClassReturnsNullIfTheHandleComponentIsEmpty()
    {
        $this->assertNull((new RefreshStatus())->resolveJobClass('aws-s3::  '));
    }

    /** @test */
    public function resolveJobClassReturnsNullIfTheClassDoesNotExist()
    {
        $this->assertNull((new RefreshStatus())->resolveJobClass('aws-bro::us-east-1'));
    }
}
