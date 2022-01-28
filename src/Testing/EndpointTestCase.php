<?php

namespace Aic\Hub\Foundation\Testing;

use Aic\Hub\Foundation\Testing\Concerns\HasEndpoint;
use Aic\Hub\Foundation\Testing\ApiTestCase as BaseTestCase;

abstract class EndpointTestCase extends BaseTestCase
{
    /**
     * Define `protected $endpoint` in child classes.
     *
     * Some API resources do not have endpoints, e.g. pivots.
     */
    use HasEndpoint;
}
