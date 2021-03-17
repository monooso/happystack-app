<?php

declare(strict_types=1);

use App\Database\Migrations\SeedComponentsMigration;

final class SeedDigitalOceanComponents extends SeedComponentsMigration
{
    protected function getComponents(): array
    {
        return [
            'digitalocean::api'                 => 'API',
            'digitalocean::app-platform'        => 'App Platform',
            'digitalocean::billing'             => 'Billing',
            'digitalocean::block-storage'       => 'Block Storage',
            'digitalocean::cloud-control-panel' => 'Cloud Control Panel',
            'digitalocean::cloud-firewall'      => 'Cloud Firewall',
            'digitalocean::community'           => 'Community',
            'digitalocean::container-registry'  => 'Container Registry',
            'digitalocean::dns'                 => 'DNS',
            'digitalocean::droplets'            => 'Droplets',
            'digitalocean::event-processing'    => 'Event Processing',
            'digitalocean::kubernetes'          => 'Kubernetes',
            'digitalocean::load-balancers'      => 'Load Balancers',
            'digitalocean::managed-databases'   => 'Managed Databases',
            'digitalocean::monitoring'          => 'Monitoring',
            'digitalocean::networking'          => 'Networking',
            'digitalocean::spaces'              => 'Spaces',
            'digitalocean::spaces-cdn'          => 'Spaces CDN',
            'digitalocean::support-center'      => 'Support Center',
            'digitalocean::vpc'                 => 'VPC',
        ];
    }

    protected function getServiceKey(): string
    {
        return 'digitalocean';
    }
}
