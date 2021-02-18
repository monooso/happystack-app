<?php

declare(strict_types=1);

namespace App\Contracts\Parsers;

use App\Exceptions\UnknownComponentException;
use App\Exceptions\UnknownStatusException;
use App\PlainObjects\ComponentStatus;
use Psr\Http\Message\ResponseInterface;

interface StatusPageParser
{
    /**
     * Extract the status of the specified component from the given payload
     *
     * @param string            $componentId
     * @param ResponseInterface $payload
     *
     * @return ComponentStatus
     *
     * @throws UnknownComponentException
     * @throws UnknownStatusException
     */
    public function parse(string $componentId, ResponseInterface $payload): ComponentStatus;
}
