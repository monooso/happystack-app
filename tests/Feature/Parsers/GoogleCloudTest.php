<?php

declare(strict_types=1);

namespace Tests\Feature\Parsers;

use App\Constants\Status;
use App\Fetchers\GoogleCloud as GoogleCloudFetcher;
use App\Parsers\GoogleCloud as GoogleCloudParser;
use Illuminate\Foundation\Testing\WithFaker;
use Symfony\Component\DomCrawler\Crawler;
use Tests\TestCase;

final class GoogleCloudTest extends TestCase
{
    use WithFaker;

    private Crawler $crawler;

    protected function setUp(): void
    {
        parent::setUp();

        // Fetch the live status
        $this->crawler = (new GoogleCloudFetcher())->fetch();
    }

    /** @test */
    public function itReturnsTheStatusOfAKnownComponent()
    {
        $status = (new GoogleCloudParser())->parse(
            'google-cloud-sql',
            $this->crawler
        );

        $this->assertContains($status, Status::known());
    }

    /** @test */
    public function itReturnsUnknownIfTheComponentDoesNotExist()
    {
        $status = (new GoogleCloudParser())->parse(
            $this->faker->sentence(),
            $this->crawler
        );

        $this->assertSame(Status::UNKNOWN, $status);
    }
}
