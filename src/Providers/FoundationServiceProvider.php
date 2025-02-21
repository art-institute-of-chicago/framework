<?php

namespace Aic\Hub\Foundation\Providers;

use Illuminate\Support\AggregateServiceProvider;

class FoundationServiceProvider extends AggregateServiceProvider
{
    /**
     * These providers will be auto-loaded, so only put harmless stuff here.
     */
    protected $providers = [
        CommandServiceProvider::class,
    ];
}
