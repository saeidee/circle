<?php

namespace App\ValueObjects\Circuit;

/**
 * Class CircuitKeys
 * @package App\ValueObjects\Circuit
 */
final class CircuitKeys
{
    const OPEN_KEY = ':circuit:open';
    const ATTEMPT_KEY = ':circuit:attempts';
    const UNAVAILABLE_KEY = ':circuit:unavailable';

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
    public function getUnavailableKey(): string
    {
        return $this->prefix . self::UNAVAILABLE_KEY;
    }

    /**
     * @return string
     */
    public function getAttemptKeys(): string
    {
        return $this->prefix . self::ATTEMPT_KEY;
    }
}
