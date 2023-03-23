<?php

declare(strict_types=1);

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;

final class ClientComponentStatusChanged extends Mailable
{
    use Queueable;

    public function __construct(public string $body)
    {
    }

    public function build(): self
    {
        return $this->text('emails.status.client');
    }
}
