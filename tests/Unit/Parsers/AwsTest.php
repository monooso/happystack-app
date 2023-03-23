<?php

namespace Tests\Unit\Parsers;

use App\Constants\Status;
use App\Exceptions\UnknownStatusException;
use App\Parsers\Aws;
use GuzzleHttp\Psr7\Response;
use Illuminate\Support\Carbon;
use Tests\TestCase;

class AwsTest extends TestCase
{
    /**
     * Generate a response body
     *
     * Only includes the fields we care about
     */
    private function makeResponseBody(): string
    {
        $archive = [
            [
                'service' => 's3-us-standard',
                'status' => 2,
                'date' => Carbon::now()->subDays(3)->getTimestamp(),
            ],
            [
                'service' => 's3-us-standard',
                'status' => 1,
                'date' => Carbon::now()->subDays(2)->getTimestamp(),
            ],
            [
                'service' => 's3-us-east-2',
                'status' => 3,
                'date' => Carbon::now()->subDays(1)->getTimestamp(),
            ],
            [
                'service' => 'rogue-component',
                'status' => 0,
                'date' => Carbon::now()->getTimestamp(),
            ],
        ];

        return json_encode(['archive' => $archive]);
    }

    /** @test */
    public function parseExtractsTheComponentStatus()
    {
        $payload = new Response(200, [], $this->makeResponseBody());

        $status = (new Aws())->parse('s3-us-standard', $payload);

        $this->assertSame(Status::OKAY, $status);
    }

    /**
     * If a component is missing from the AWS feed, it means it's operational
     *
     * @test
     */
    public function parseReturnsOkayIfTheComponentDoesNotExist()
    {
        $payload = new Response(200, [], $this->makeResponseBody());

        $status = (new Aws())->parse('sqs-ap-northeast-1', $payload);

        $this->assertSame(Status::OKAY, $status);
    }

    /** @test */
    public function parseThrowsAnExceptionIfTheComponentStatusIsNotRecognized()
    {
        $payload = new Response(200, [], $this->makeResponseBody());

        $this->expectException(UnknownStatusException::class);

        (new Aws())->parse('rogue-component', $payload);
    }
}
