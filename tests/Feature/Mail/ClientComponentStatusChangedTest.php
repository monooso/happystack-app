<?php

declare(strict_types=1);

namespace Tests\Feature\Mail;

use App\Mail\ClientComponentStatusChanged;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

final class ClientComponentStatusChangedTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

    /** @test */
    public function itOutputsTheGivenMessage()
    {
        $message = $this->faker->realText();
        $mailable = new ClientComponentStatusChanged($message);

        $mailable->assertSeeInHtml($message);
        $mailable->assertSeeInText($message);
    }
}
