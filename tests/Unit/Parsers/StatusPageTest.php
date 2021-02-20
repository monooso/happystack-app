<?php

namespace Tests\Unit\Parsers;

use App\Constants\Status;
use App\Exceptions\UnknownComponentException;
use App\Exceptions\UnknownStatusException;
use App\Parsers\StatusPage;
use DateTime;
use GuzzleHttp\Psr7\Response;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class StatusPageTest extends TestCase
{
    use WithFaker;

    /**
     * Generate a response body
     *
     * Only includes the fields we care about
     *
     * @return string
     */
    private function makeResponseBody(): string
    {
        $components = [
            [
                'id'         => 'operational-component',
                'status'     => 'operational',
                'created_at' => $this->faker->dateTimeThisDecade()->format(DateTime::ISO8601),
                'updated_at' => $this->faker->dateTimeThisMonth()->format(DateTime::ISO8601),
            ],
            [
                'id'         => 'rogue-component',
                'status'     => 'invalid-status',
                'created_at' => $this->faker->dateTimeThisDecade()->format(DateTime::ISO8601),
                'updated_at' => $this->faker->dateTimeThisMonth()->format(DateTime::ISO8601),
            ],
        ];

        return json_encode(['components' => $components]);
    }

    /** @test */
    public function parseExtractsTheComponentStatus()
    {
        $payload = new Response(200, [], $this->makeResponseBody());

        $status = (new StatusPage())->parse('operational-component', $payload);

        $this->assertSame(Status::OKAY, $status);
    }

    /** @test */
    public function parseThrowsAnExceptionIfTheComponentDoesNotExist()
    {
        $payload = new Response(200, [], $this->makeResponseBody());

        $this->expectExceptionObject(new UnknownComponentException('nope'));

        (new StatusPage())->parse('nope', $payload);
    }

    /** @test */
    public function parseThrowsAnExceptionIfTheComponentStatusIsNotRecognized()
    {
        $payload = new Response(200, [], $this->makeResponseBody());

        $this->expectException(UnknownStatusException::class);

        (new StatusPage())->parse('rogue-component', $payload);
    }
}
