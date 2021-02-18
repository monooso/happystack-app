<?php

declare(strict_types=1);

namespace App\Parsers;

use App\Contracts\Parsers\StatusPageParser;
use App\Exceptions\UnknownComponentException;
use App\Normalizers\StatusPageStatus;
use App\PlainObjects\ComponentStatus;
use Psr\Http\Message\ResponseInterface;

final class StatusPage implements StatusPageParser
{
    public function parse(string $componentId, ResponseInterface $payload): ComponentStatus
    {
        $responseBody = $this->getResponseBody($payload);

        $component = $this->getComponentById($componentId, $responseBody);

        $status = StatusPageStatus::normalize($component['status']);

        return (new ComponentStatus())->setStatus($status);
    }

    /**
     * Extract and parse the body of the given response
     *
     * @param ResponseInterface $response
     *
     * @return array
     */
    private function getResponseBody(ResponseInterface $response): array
    {
        return json_decode((string) $response->getBody(), true);
    }

    /**
     * Extract the component with the given ID from the response body
     *
     * @param string $componentId
     * @param array  $responseBody
     *
     * @return array
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
