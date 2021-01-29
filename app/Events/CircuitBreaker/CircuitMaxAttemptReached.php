<?php

namespace App\Events\CircuitBreaker;

use App\Events\CircuitBreaker\AbstractCircuitEvent;

/**
 * Class CircuitMaxAttemptReached
 * @package App\Events
 */
class CircuitMaxAttemptReached extends AbstractCircuitEvent
{
    /**
     * @return string
     */
    public function getType(): string
    {
        return 'MAX ATTEMPT';
    }
}
