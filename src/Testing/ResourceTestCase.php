<?php

namespace Aic\Hub\Foundation\Testing;

use Aic\Hub\Foundation\Concerns\HasAbstractProperties;
use Aic\Hub\Foundation\Testing\Concerns\HasFields;
use Aic\Hub\Foundation\Testing\FeatureTestCase as BaseTestCase;

abstract class ResourceTestCase extends BaseTestCase
{
    use HasAbstractProperties;

    /**
     * Define `protected function fields()` in child classes.
     */
    use HasFields;

    /**
     * Required. Ex: \App\Models\Artworks::class
     */
    protected $model;

    protected function setUp(): void
    {
        $this->checkProperty('model');

        parent::setUp();
    }
}
