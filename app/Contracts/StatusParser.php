<?php

declare(strict_types=1);

namespace App\Contracts;

use App\PlainObjects\StatusUpdate;
use Psr\Http\Message\ResponseInterface;

interface StatusParser
{
    /**
     * Parse the status response
     *
     * @param ResponseInterface $response
     *
     * @return StatusUpdate[]
     */
    public function parse(ResponseInterface $response): array;
}
