<?php

declare(strict_types=1);

namespace App\Parsers;

use App\Contracts\Parsers\StatusPageParser;
use App\Exceptions\UnknownComponentException;
use App\Normalizers\StatusPageStatus;
use Psr\Http\Message\ResponseInterface;

final class StatusPage implements StatusPageParser
{
    public function parse(string $componentId, ResponseInterface $payload): string
    {
        $responseBody = $this->getResponseBody($payload);

        $component = $this->getComponentById($componentId, $responseBody);

        return StatusPageStatus::normalize($component['status']);
    }

    /**
     * Extract and parse the body of the given response
     */
    private function getResponseBody(ResponseInterface $response): array
    {
        return json_decode((string) $response->getBody(), true);
    }

    /**
     * Extract the component with the given ID from the response body
     *
     * @throws UnknownComponentException
     */
    private function getComponentById(string $componentId, array $responseBody): array
    {
        $component = collect($responseBody['components'])->firstWhere('id', $componentId);

        if ($component === null) {
            throw new UnknownComponentException($componentId);
        }

        return $component;
    }
}
