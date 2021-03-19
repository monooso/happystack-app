<?php

declare(strict_types=1);

namespace App\Parsers;

use App\Constants\Status;
use App\Contracts\Parsers\GoogleCloudParser;
use App\Normalizers\GoogleCloudStatus;
use Illuminate\Support\Str;
use Symfony\Component\DomCrawler\Crawler;

final class GoogleCloud implements GoogleCloudParser
{
    public function parse(string $componentId, Crawler $crawler): string
    {
        $map = collect($this->getServiceStatusRows($crawler))
            ->map(fn ($row) => $this->parseRow($row))
            ->filter()
            ->mapWithKeys(fn ($s) => [$s[0] => $s[1]]);

        return $map->get($componentId, Status::UNKNOWN);
    }

    /**
     * Extract the "status" table rows from the DOM
     *
     * @param Crawler $crawler
     *
     * @return array
     */
    private function getServiceStatusRows(Crawler $crawler): array
    {
        return $crawler
            ->filter('td.service-status')
            ->each(fn (Crawler $td) => $td->closest('tr'));
    }

    /**
     * Parse a "status" table row
     *
     * Returns a tuple containing the service key and status. For example:
     * ['google-cloud-storage', 'okay']
     *
     * @param Crawler $row
     *
     * @return array|null
     */
    private function parseRow(Crawler $row): ?array
    {
        $cells = $row->filter('td');
        $keyCell = $cells->first();
        $statusCell = $cells->filter('td > span')->last();

        if ($keyCell->count() === 0 || $statusCell->count() === 0) {
            return null;
        }

        $key = $this->convertServiceNameToKey($keyCell->text());
        $status = GoogleCloudStatus::normalize($statusCell->attr('class'));

        return [$key, $status];
    }

    /**
     * Convert a human-readable service name to a "key"
     *
     * Example:
     * ' Google Cloud SQL ' -> 'google-cloud-sql'
     *
     * @param string $name
     *
     * @return string
     */
    private function convertServiceNameToKey(string $name): string
    {
        return Str::kebab(Str::lower(trim($name)));
    }
}
