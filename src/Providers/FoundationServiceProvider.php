<?php

namespace Aic\Hub\Foundation\Providers;

use Illuminate\Support\ServiceProvider;

class FoundationServiceProvider extends ServiceProvider
{
    /**
     * This will be auto-loaded, so only put harmless stuff here.
     */
    public function boot()
    {
        if ($this->app->runningInConsole()) {
            $this->commands([
                \Aic\Hub\Foundation\Commands\DatabaseReset::class,
                \Aic\Hub\Foundation\Commands\MakeUser::class,
            ]);
        }
    }
}
