<?php

namespace App\Providers;

use App\Actions\Projects\CreateProject;
use App\Contracts\CreatesProjects;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->bind(CreatesProjects::class, CreateProject::class);
    }
}
