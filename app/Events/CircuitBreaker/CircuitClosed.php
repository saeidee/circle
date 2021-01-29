<?php

namespace App\Events\CircuitBreaker;

use App\Events\CircuitBreaker\AbstractCircuitEvent;

/**
 * Class CircuitClosed
 * @package App\Events
 */
class CircuitClosed extends AbstractCircuitEvent
{
    /**
     * @return string
     */
    public function getType(): string
    {
        return 'CLOSED';
    }
}
