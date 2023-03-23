<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Actions\Components\RefreshStatus;
use App\Models\Component;
use Exception;
use Illuminate\Console\Command;

final class RefreshStatuses extends Command
{
    protected $signature = 'happy:refresh-statuses';

    protected $description = 'Refresh outdated component statuses.';

    public function handle()
    {
        $action = new RefreshStatus();

        Component::stale()->each(function (Component $component) use ($action) {
            $handle = $component->handle;

            try {
                $action->refresh($component);
            } catch (Exception $e) {
                // @todo log exceptions with extreme prejudice
                $message = "Error updating ${handle}: ".$e->getMessage();
                $this->error('⚠️ '.$message);

                return;
            }

            $this->info('⚡️ Updating '.$handle);
        });

        $this->info('💪 All done');

        return 0;
    }
}
