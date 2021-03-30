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

    public function __construct(public string $body)
    {
    }

    public function build()
    {
        return $this->text('emails.status.client');
    }
}
