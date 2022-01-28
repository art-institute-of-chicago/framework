<?php

namespace Aic\Hub\Foundation\Testing;

use Tests\CreatesApplication;
use Aic\Hub\Foundation\Concerns\HasAbstractProperties;
use Aic\Hub\Foundation\Testing\Concerns\HasEndpoint;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class ApiTestCase extends BaseTestCase
{
    use HasAbstractProperties;

    /**
     * It is reasonable to expect that this trait must exist. See docs.
     *
     * @link https://laravel.com/docs/8.x/testing#the-creates-application-trait
     */
    use CreatesApplication;

    /**
     * @link https://laravel.com/docs/8.x/database-testing#resetting-the-database-after-each-test
     */
    use RefreshDatabase;

    /**
     * Define `protected $endpoint` in child classes.
     *
     * @todo Consider making endpoint optional, e.g. for testing pivots.
     */
    use HasEndpoint;

    /**
     * Required. Ex: \App\Models\Artworks::class
     */
    protected $model;

    protected function setUp(): void
    {
        $this->checkProperty('model');

        foreach (class_uses_recursive($this) as $trait) {
            if (method_exists($this, $method = 'setUp' . class_basename($trait))) {
                $this->{$method}();
            }
        }

        parent::setUp();
    }

}
