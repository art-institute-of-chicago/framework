<?php

namespace Aic\Hub\Foundation;

use Aic\Hub\Foundation\Factories\Concerns\HasNumericId;
use Illuminate\Database\Eloquent\Factories\Factory;

abstract class AbstractFactory extends Factory
{
    use HasNumericId;

    abstract public function nullable();

    protected function getTitle()
    {
        return ucfirst($this->faker->words(3, true));
    }
}
