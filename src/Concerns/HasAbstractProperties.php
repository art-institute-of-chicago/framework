<?php

namespace Aic\Hub\Foundation\Concerns;

use LogicException;

trait HasAbstractProperties
{
    /**
     * Use this trait when your abstract class has `null` properties,
     * which must be defined as non-null by children of that class.
     *
     * Work-around for "Properties cannot be declared abstract" error.
     */
    protected function checkProperty(string $property)
    {
        if (!isset($this->{$property})) {
            throw new LogicException(get_class($this) . ' missing $' . $property);
        }
    }
}
