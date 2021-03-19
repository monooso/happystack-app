<?php

declare(strict_types=1);

namespace Tests\Unit\Jobs;

use App\Exceptions\FetchJobResolutionException;
use App\Jobs\AwsS3\FetchApEast1Status;
use App\Jobs\FetchJobResolver;
use PHPUnit\Framework\TestCase;

final class FetchJobResolverTest extends TestCase
{
    /** @test */
    public function resolveReturnsTheFullQualifiedClassName()
    {
        $handle = 'aws-s3::ap-east-1';

        $jobClass = FetchJobResolver::resolve($handle);

        $this->assertSame(FetchApEast1Status::class, ltrim($jobClass, '\\'));
    }

    /** @test */
    public function resolveThrowsAnExceptionIfTheJobCannotBeResolved()
    {
        $this->expectException(FetchJobResolutionException::class);

        FetchJobResolver::resolve('alfa::bravo');
    }

    /** @test */
    public function resolveThrowsAnExceptionIfTheComponentHandleIsInvalid()
    {
        $this->expectException(FetchJobResolutionException::class);

        FetchJobResolver::resolve('charlie');
    }
}
