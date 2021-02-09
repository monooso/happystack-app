<?php

declare(strict_types=1);

namespace App\Parsers;

use App\Constants\Status;
use App\Contracts\StatusParser;
use App\PlainObjects\StatusUpdate;
use DateTime;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Config;
use Psr\Http\Message\ResponseInterface;

final class StatusPage implements StatusParser
{
    private string $serviceKey;

    /**
     * Constructor
     *
     * @param string $serviceKey For example, 'mailgun'
     */
    public function __construct(string $serviceKey)
    {
        $this->serviceKey = $serviceKey;
    }

    public function parse(ResponseInterface $response): array
    {
        $json = json_decode((string) $response->getBody());

        $components = $this->filterComponents(collect($json->components));

        return $this->normalizeComponents($components);
    }

    /**
     * Remove any unsupported components from the collection
     *
     * @param Collection $components
     *
     * @return Collection
     */
    private function filterComponents(Collection $components): Collection
    {
        $map = $this->getComponentIds();

        return $components->filter(fn ($c) => in_array($c->id, $map, true));
    }

    /**
     * Get the IDs of the supported StatusPage components
     *
     * @return array
     */
    private function getComponentIds(): array
    {
        $components = $this->getComponents();

        return collect($components)->map(fn ($c) => $c['id'])->values()->all();
    }

    /**
     * Get the component definitions from the service config file
     *
     * @return array
     */
    private function getComponents(): array
    {
        return Arr::get($this->getServiceConfig(), 'components', []);
    }

    /**
     * Get the service config
     *
     * @return array
     */
    private function getServiceConfig(): array
    {
        return Config::get('happystack.services.' . $this->serviceKey, []);
    }

    /**
     * Normalise all of the component status updates
     *
     * @param Collection $components
     *
     * @return StatusUpdate[]
     */
    private function normalizeComponents(Collection $components): array
    {
        return $components->map(fn ($c) => $this->normalizeComponent($c))->all();
    }

    /**
     * Normalise a single component status update
     *
     * @param $component
     *
     * @return StatusUpdate
     */
    private function normalizeComponent($component): StatusUpdate
    {
        $config = $this->getComponentConfigById($component->id);

        return (new StatusUpdate())
            ->setComponent($config['key'])
            ->setService($this->serviceKey)
            ->setStatus($this->normalizeStatus($component->status))
            ->setRetrievedAt(new DateTime('now'));
    }

    /**
     * Get the config for the component with the given StatusPage id
     *
     * @param string $id
     *
     * @return array
     */
    private function getComponentConfigById(string $id): array
    {
        $components = $this->getComponents();

        return collect($components)->firstWhere('id', $id);
    }

    /**
     * Convert a statuspage.io status into an internal status
     *
     * @param string $status
     *
     * @return string
     */
    private function normalizeStatus(string $status): string
    {
        $config = $this->getServiceConfig();

        return Arr::get($config, 'statuses.' . $status, Status::UNKNOWN);
    }
}
