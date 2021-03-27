<?php

declare(strict_types=1);

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

final class ClientComponentStatusChanged extends Mailable
{
    use Queueable;
    use SerializesModels;

    public string $body;

    public function __construct(string $body)
    {
        // Horrible hack, to handle single line-breaks
        $this->body = str_replace(PHP_EOL, '  ' . PHP_EOL, $body);
    }

    public function build()
    {
        return $this->markdown('emails.status.client');
    }
}
