<?php

namespace Tests\Fakes;

use App\Events\CircuitBreaker\AbstractCircuitEvent;
use Illuminate\Foundation\Testing\WithFaker;

/**
 * Class FakeAbstractCircuitEvent
 * @package Tests\Fakes
 */
class FakeAbstractCircuitEvent extends AbstractCircuitEvent
{
    use WithFaker;

    /**
     * @return string
     */
    public function getType(): string
    {
        return $this->faker->name;
    }
}
