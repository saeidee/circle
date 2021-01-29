<?php

namespace App\Events\CircuitBreaker;

/**
 * Class AbstractCircuitEvent
 * @package App\Events\CircuitBreaker
 */
abstract class AbstractCircuitEvent
{
    /** @var string */
    protected $circuitName;

    /**
     * AbstractCircuitEvent constructor.
     * @param string $circuitName
     */
    public function __construct(string $circuitName)
    {
        $this->circuitName = $circuitName;
    }

    /**
     * @return string
     */
    abstract public function getType(): string;

    /**
     * @return string
     */
    public function getMessage(): string
    {
        return "{$this->circuitName} circuit is now " . $this->getType();
    }
}
