<?php

namespace Aic\Hub\Foundation\Providers;

use Illuminate\Support\ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * Load authentication-related migrations. Consider splitting the `migrations` directory
     * if we ever need to add migrations that do not concern authentication.
     *
     * @link https://laravel.com/docs/5.8/packages#migrations
     *
     * @return void
     */
    public function boot()
    {
        $this->loadMigrationsFrom(__DIR__ . '/../migrations');
    }
}
