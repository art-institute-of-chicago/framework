<?php

namespace Aic\Hub\Foundation\Providers;

use Illuminate\Support\ServiceProvider;

class DefaultConfigServiceProvider extends ServiceProvider
{
    /**
     * Path to directory where default config files are stored.
     * They will be merged with any provided by the application.
     *
     * @var string
     */
    private $defaultConfigPath = __DIR__ . '/../../config/default';

    /**
     * Path to directory where published config files are stored.
     *
     * @var string
     */
    private $publishConfigPath = __DIR__ . '/../../config/publish';

    /**
     * This publishes the required `config` files to your app, which from
     * testing contain the only settings that *must* live in the app,
     * instead of the foundation. Run this to publish them:
     *
     * php artisan vendor:publish --provider="Aic\Hub\Foundation\Providers\DefaultConfigServiceProvider" --force
     *
     * Note the quotes. This may overwrite your existing `config` files!
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([
            $this->publishConfigPath . '/app.php' => config_path('app.php'),
            $this->publishConfigPath . '/databases.php' => config_path('databases.php'),
            $this->publishConfigPath . '/view.php' => config_path('view.php'),
        ]);
    }

    /**
     * This provider merges default config files from the foundation with the
     * ones in your app. This enables us to omit them from our apps, reducing
     * code duplication across microservices.
     *
     * If you need to customize configs, try to use `.env` settings whenever
     * possible. If that's not possible, you can copy default config files to
     * your app, and omit any first-level keys you don't want to customize.
     *
     * There is one limitation to this approach: you must always include the
     * `config/app.php` file in your app, with a few required keys. See the
     * `boot` method here for more info.
     *
     * @link https://laravel.com/docs/5.5/packages#resources
     * @link https://laracasts.com/discuss/channels/general-discussion/how-does-mergeconfigfrom-work
     *
     * @return void
     */
    public function register()
    {
        $files = glob($this->defaultConfigPath . '/*.php');

        foreach ($files as $file) {
            $this->mergeConfigFrom($file, basename($file, '.php'));
        }

        // Uncomment this for debugging
        // dd( $this->app['config'] );
    }
}
