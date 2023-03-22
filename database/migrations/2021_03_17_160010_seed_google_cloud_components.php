<?php

declare(strict_types=1);

use App\Database\Migrations\SeedComponentsMigration;

return new class extends SeedComponentsMigration
{
    protected function getComponents(): array
    {
        return [
            'google-cloud::app-engine' => 'App Engine',
            'google-cloud::big-query' => 'BigQuery',
            'google-cloud::cloud-bigtable' => 'Cloud Bigtable',
            'google-cloud::cloud-spanner' => 'Cloud Spanner',
            'google-cloud::cloud-storage' => 'Cloud Storage',
            'google-cloud::cloud-run' => 'Cloud Run',
            'google-cloud::cloud-sql' => 'Cloud SQL',
            'google-cloud::compute-engine' => 'Compute Engine',
            'google-cloud::dataflow' => 'Dataflow',
            'google-cloud::firestore' => 'Firestore',
            'google-cloud::kubernetes-engine' => 'Google Kubernetes Engine',
            'google-cloud::memorystore' => 'Memorystore',
            'google-cloud::operations' => 'Operations',
            'google-cloud::workflows' => 'Workflows',
        ];
    }

    protected function getServiceKey(): string
    {
        return 'google-cloud';
    }
};
