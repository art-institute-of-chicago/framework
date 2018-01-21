<?php

namespace Aic\Hub\Foundation;

use Illuminate\Support\ServiceProvider;

class DefaultConfigServiceProvider extends ServiceProvider
{

    /**
     * Path to directory where default config files are stored.
     * They will be merged with any provided by the application.
     *
     * @var string
     */
    private $defaultConfigPath = __DIR__.'/../config';

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {

        // List all PHP files in ../config
        $files = glob( $this->defaultConfigPath . '/*.php' );

        foreach( $files as $file )
        {

            // https://laracasts.com/discuss/channels/general-discussion/how-does-mergeconfigfrom-work
            $this->mergeConfigFrom( $file, basename( $file, '.php' ) );

        }

        // Uncomment this for debugging
        // dd( $this->app['config'] );

    }

}
