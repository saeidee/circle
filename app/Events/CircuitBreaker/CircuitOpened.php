<?php

namespace App\Events\CircuitBreaker;

use App\Events\CircuitBreaker\AbstractCircuitEvent;

/**
 * Class CircuitOpened
 * @package App\Events
 */
class CircuitOpened extends AbstractCircuitEvent
{
    /**
     * @return string
     */
    public function getType(): string
    {
        return 'OPENED';
    }
}
