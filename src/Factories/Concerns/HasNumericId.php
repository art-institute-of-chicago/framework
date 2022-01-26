<?php

namespace Aic\Hub\Foundation\Factories\Concerns;

trait HasNumericId
{
    public function getValidId()
    {
        return $this->faker->unique()->randomNumber(6);
    }

    public function getInvalidId()
    {
        return $this->faker->uuid();
    }
}
