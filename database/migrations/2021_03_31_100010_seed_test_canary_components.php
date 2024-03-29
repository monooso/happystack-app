<?php

declare(strict_types=1);

use App\Database\Migrations\SeedComponentsMigration;

return new class extends SeedComponentsMigration
{
    protected function getComponents(): array
    {
        return [
            'test-canary::alfa' => 'Alfa',
            'test-canary::bravo' => 'Bravo',
            'test-canary::charlie' => 'Charlie',
        ];
    }

    protected function getServiceKey(): string
    {
        return 'test-canary';
    }
};
