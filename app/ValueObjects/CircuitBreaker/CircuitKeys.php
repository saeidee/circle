<?php

namespace App\ValueObjects\CircuitBreaker;

/**
 * Class CircuitKeys
 * @package App\ValueObjects\CircuitBreaker
 */
final class CircuitKeys
{
    const OPEN_KEY = ':circuit:open';
    const HALF_OPEN_KEY = ':circuit:half-open';
    const ATTEMPT_KEY = ':circuit:attempts';

    /** @var string */
    private $prefix;

    /**
     * CircuitKeys constructor.
     * @param string $prefix
     */
    public function __construct(string $prefix)
    {
        $this->prefix = $prefix;
    }

    /**
     * @return string
     */
    public function getOpenKey(): string
    {
        return $this->prefix . self::OPEN_KEY;
    }

    /**
     * @return string
     */
    public function getHalfOpenKey(): string
    {
        return $this->prefix . self::HALF_OPEN_KEY;
    }

    /**
     * @return string
     */
    public function getAttemptKey(): string
    {
        return $this->prefix . self::ATTEMPT_KEY;
    }

    /**
     * @return string
     */
    public function getPrefix(): string
    {
        return $this->prefix;
    }
}
