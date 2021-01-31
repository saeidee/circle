<?php

namespace App\Factories;

use App\Services\CircuitBreaker\CircuitTracker;
use App\ValueObjects\CircuitBreaker\CircuitKeys;

/**
 * Class CircuitTrackerFactory
 * @package App\Factories
 */
class CircuitTrackerFactory
{
    /**
     * @param string $circuitPrefix
     * @return CircuitTracker
     */
    public function make(string $circuitPrefix): CircuitTracker
    {
        return new CircuitTracker(new CircuitKeys($circuitPrefix));
    }
}
