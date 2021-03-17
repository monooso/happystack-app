<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Models\Component;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;

final class RefreshStatuses extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'happy:refresh-statuses';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Refresh outdated component statuses.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        // Retrieve all of the outdated (?) components from the database
        // @todo set as environment or config variable
        $threshold = Carbon::now()->subMinutes(5);

        $components = Component::where('updated_at', '<=', $threshold)->get();

        // For each component:
        // - Check the class exists
        // - Queue the job

        foreach ($components as $component) {
            // - Determine the job class
            //   aws-s3::ap-east-1 -> App\Jobs\AwsS3\FetchApEast1Status
            // @todo move to "resolver" helper class

            [$serviceName, $componentName] = explode('::', $component->handle);

            $serviceNamespace = ucfirst(Str::camel($serviceName));
            $componentClass = 'Fetch' . ucfirst(Str::camel($componentName)) . 'Status';
            $fqcn = '\\App\\Jobs\\' . $serviceNamespace . '\\' . $componentClass;

            if (!class_exists($fqcn)) {
                // @todo log something, somewhere
                $this->info('💡 The ' . $fqcn . ' class does not exist');
                continue;
            }

            $fqcn::dispatch($component);
            $this->info('⚡️ Dispatched ' . $fqcn);
        }

        return 0;
    }
}
