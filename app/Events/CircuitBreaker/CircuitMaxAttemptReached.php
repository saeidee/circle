<?php

namespace App\Events\CircuitBreaker;

/**
 * Class CircuitMaxAttemptReached
 * @package App\Events
 */
class CircuitMaxAttemptReached extends AbstractCircuitEvent
{
    const TYPE = 'MAX ATTEMPT';

    /**
     * @return string
     */
    public function getType(): string
    {
        return self::TYPE;
    }
}
