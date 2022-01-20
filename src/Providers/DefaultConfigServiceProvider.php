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
    private $defaultConfigPath = __DIR__ . '/../config/default';

    /**
     * Path to directory where published config files are stored.
     *
     * @var string
     */
    private $publishConfigPath = __DIR__ . '/../config/publish';

    /**
     * This publishes the required `config/app.php` file to your app, which
     * contains the only settings that must live in the app, instead of the
     * foundation: `providers`, `aliases`, `timezone`, and `env`. Run this
     * to publish it:
     *
     * php artisan vendor:publish --provider="Aic\Hub\Foundation\Providers\DefaultConfigServiceProvider" --force
     *
     * Note the quotes. This will overwrite your existing `config/app.php`.
     *
     * @return void
     */
    public function boot()
    {

        $this->publishes([
            $this->publishConfigPath . '/app.php' => config_path('app.php'),
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
     *
     * @return void
     */
    public function register()
    {

        $files = glob($this->defaultConfigPath . '/*.php');

        foreach($files as $file)
        {

            // https://laracasts.com/discuss/channels/general-discussion/how-does-mergeconfigfrom-work
            $this->mergeConfigFrom($file, basename($file, '.php'));

        }

        // Uncomment this for debugging
        // dd( $this->app['config'] );

    }

}
