<?php

declare(strict_types=1);

namespace App\Contracts\Parsers;

use App\Exceptions\UnknownComponentException;
use App\Exceptions\UnknownStatusException;
use Psr\Http\Message\ResponseInterface;

interface AwsParser
{
    /**
     * Extract the status of the specified component from the given payload
     *
     * @throws UnknownComponentException
     * @throws UnknownStatusException
     */
    public function parse(string $componentId, ResponseInterface $payload): string;
}
