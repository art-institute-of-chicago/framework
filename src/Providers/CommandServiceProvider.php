<?php

namespace Aic\Hub\Foundation\Providers;

use Illuminate\Support\ServiceProvider;

class CommandServiceProvider extends ServiceProvider
{
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
