<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Constants\AwsRegion;
use Exception;
use Illuminate\Console\Command;
use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

final class AwsJobsMakeCommand extends Command
{
    protected $signature = 'happy:make-aws-jobs {service : The AWS service (e.g. ses)}';

    protected $description = 'Create the jobs for a given AWS service';

    public function handle()
    {
        $service = $this->argument('service');

        $directoryName = $this->pascal('aws-' . $service);
        $directoryPath = app_path('Jobs/' . $directoryName);

        try {
            File::makeDirectory($directoryPath);
        } catch (Exception $e) {
            $this->error('Unable to create directory ' . $directoryPath);
            $this->error('Details: ' . $e->getMessage());
            return 1;
        }

        foreach (AwsRegion::all() as $region) {
            $className = 'Fetch' . $this->pascal($region) . 'Status';
            $filePath = $directoryPath . '/' . $className . '.php';

            $props = [
                'class'       => $className,
                'componentId' => $service . '-' . $region,
                'namespace'   => 'App\\Jobs\\' . $directoryName,
            ];

            try {
                $template = $this->getTemplateString();
            } catch (FileNotFoundException $e) {
                $this->error('Could not find template file');
                $this->error('Details: ' . $e->getMessage());
                return 1;
            }

            $rendered = $this->parseTemplate($template, $props);

            File::put($filePath, $rendered);

            $this->info('Generated ' . $filePath);
        }

        return 0;
    }

    /**
     * Load the template file contents
     *
     * @return string
     * @throws FileNotFoundException
     */
    private function getTemplateString(): string
    {
        return File::get(resource_path('views/generators/aws-job.stub'));
    }

    /**
     * Parse the given template string
     *
     * @param string $template
     * @param array  $props
     *
     * @return string
     */
    private function parseTemplate(string $template, array $props): string
    {
        return collect($props)->reduce(function ($template, $value, $key) {
            $keys = ['{{ ' . $key . ' }}', '{{' . $key . '}}'];
            return str_replace($keys, $value, $template);
        }, $template);
    }

    /**
     * Convert a string to Pascal case
     *
     * @param string $input
     *
     * @return string
     */
    private function pascal(string $input): string
    {
        return Str::ucfirst(Str::camel($input));
    }
}
