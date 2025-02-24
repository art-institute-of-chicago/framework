<?php

namespace Aic\Hub\Foundation\Testing;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\RefreshDatabaseState;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class FeatureTestCase extends BaseTestCase
{
    /**
     * @link https://laravel.com/docs/8.x/database-testing#resetting-the-database-after-each-test
     */
    use RefreshDatabase;

    protected function setUp(): void
    {
        if (property_exists($this, 'forceRefresh') && $this->forceRefresh) {
            RefreshDatabaseState::$migrated = false;
        }

        parent::setUp();

        foreach (class_uses_recursive($this) as $trait) {
            if (method_exists($this, $method = 'setUp' . class_basename($trait))) {
                $this->{$method}();
            }
        }
    }
}
