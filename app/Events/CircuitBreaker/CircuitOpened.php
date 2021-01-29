<?php

namespace App\Events\CircuitBreaker;

/**
 * Class CircuitOpened
 * @package App\Events
 */
class CircuitOpened extends AbstractCircuitEvent
{
    const TYPE = 'OPENED';

    /**
     * @return string
     */
    public function getType(): string
    {
        return self::TYPE;
    }
}
