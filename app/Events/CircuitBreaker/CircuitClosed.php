<?php

namespace App\Events\CircuitBreaker;

/**
 * Class CircuitClosed
 * @package App\Events
 */
class CircuitClosed extends AbstractCircuitEvent
{
    const TYPE = 'CLOSED';

    /**
     * @return string
     */
    public function getType(): string
    {
        return self::TYPE;
    }
}
