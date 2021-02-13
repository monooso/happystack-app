<?php

namespace Tests\Feature\Fetchers;

use App\Fetchers\StatusPage;
use GuzzleHttp\ClientInterface;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\App;
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

    /** @test */
    public function fetch_works_end_to_end()
    {
        // The Mailgun status page ID
        $pageId = '6jp439mdyy0k';

        $subject = App::makeWith(StatusPage::class, ['pageId' => $pageId]);

        $this->assertInstanceOf(ResponseInterface::class, $subject->fetch());
    }
}
