<?php

declare(strict_types=1);

namespace App\Parsers;

use App\Constants\Status;
use App\Contracts\Parsers\AwsParser;
use App\Normalizers\AwsStatus;
use Psr\Http\Message\ResponseInterface;

final class Aws implements AwsParser
{
    public function parse(string $componentId, ResponseInterface $payload): string
    {
        $responseBody = $this->getResponseBody($payload);

        $component = $this->getComponentById($componentId, $responseBody);

        // If a component is missing from the AWS feed, it means it's operational
        return ($component === null)
            ? Status::OKAY
            : AwsStatus::normalize($component['status']);
    }

    /**
     * Extract and parse the body of the given response
     */
    private function getResponseBody(ResponseInterface $response): array
    {
        return json_decode((string) $response->getBody(), true);
    }

    /**
     * Extract the most recent component with the given ID from the response body
     */
    private function getComponentById(string $componentId, array $responseBody): ?array
    {
        return collect($responseBody['archive'])
            ->sortByDesc('date', SORT_NUMERIC)
            ->firstWhere('service', $componentId);
    }
}
