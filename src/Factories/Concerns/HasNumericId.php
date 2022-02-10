<?php

namespace Aic\Hub\Foundation\Factories\Concerns;

trait HasNumericId
{
    public function getValidId()
    {
        return $this->getNumericId();
    }

    public function getInvalidId()
    {
        return $this->faker->uuid();
    }

    protected function getNumericId()
    {
        return $this->faker->unique()->randomNumber(6);
    }
}
