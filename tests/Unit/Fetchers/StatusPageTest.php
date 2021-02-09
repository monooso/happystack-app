<?php

namespace Tests\Unit\Fetchers;

use App\Fetchers\StatusPage;
use GuzzleHttp\ClientInterface;
use Illuminate\Foundation\Testing\WithFaker;
use Psr\Http\Message\ResponseInterface;
use Tests\TestCase;

class StatusPageTest extends TestCase
{
    use WithFaker;

    /** @test */
    public function fetch_uses_the_given_page_id()
    {
        $pageId = $this->faker->uuid;

        $client = $this->mock(ClientInterface::class);
        $response = $this->mock(ResponseInterface::class);

        $subject = new StatusPage($client, $pageId);

        $expectedUrl = "https://${pageId}.statuspage.io/api/v2/summary.json";

        $client->shouldReceive('request')->withArgs(['GET', $expectedUrl])->andReturn($response);

        $subject->fetch();
    }
}
