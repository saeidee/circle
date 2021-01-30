<?php

namespace App\Services\CircuitBreaker;

use App\Enums\CircuitEnums;
use Illuminate\Support\Facades\Redis;
use App\Events\CircuitBreaker\CircuitClosed;
use App\Events\CircuitBreaker\CircuitOpened;
use App\ValueObjects\CircuitBreaker\CircuitKeys;
use App\Events\CircuitBreaker\CircuitMaxAttemptReached;

/**
 * Class CircuitTracker
 * @package App\Services\CircuitBreaker
 */
class CircuitTracker
{
    const STATUS_VALUE = 1;
    const MAX_ATTEMPTS = 3;
    const INITIAL_MAX_ATTEMPT = 1;

    /** @var CircuitKeys $keys */
    private $keys;

    /**
     * CircuitTracker constructor.
     * @param CircuitKeys $keys
     */
    public function __construct(CircuitKeys $keys)
    {
        $this->keys = $keys;
    }

    /**
     * @return bool
     */
    public function isOpen(): bool
    {
        return Redis::exists($this->keys->getOpenKey()) && $this->isReachedMaxAttempt();
    }

    /**
     * @return bool
     */
    public function wasHalfOpen(): bool
    {
        return Redis::exists($this->keys->getHalfOpenKey());
    }

    /**
     * @return bool
     */
    public function isReachedMaxAttempt(): bool
    {
        return $this->getAttempts() > self::MAX_ATTEMPTS;
    }

    /**
     * @return void
     */
    public function saveFailure(): void
    {
        if (!$this->isAlreadyOpened()) {
            $this->openCircuit();

            return;
        }

        if (!$this->hasAlreadyAttempted()) {
            $this->initializeAttempt();

            return;
        }

        $this->trackAttempt();

        if ($this->isReachedMaxAttempt()) {
            event(new CircuitMaxAttemptReached($this->keys->getPrefix()));
        }
    }

    /**
     * @return void
     */
    public function saveSuccess(): void
    {
        if ($this->wasHalfOpen()) {
            event(new CircuitClosed($this->keys->getPrefix()));
        }

        $this->closeCircuit();
    }

    /**
     * @return int
     */
    protected function getAttempts(): int
    {
        return (int)Redis::get($this->keys->getAttemptKey());
    }

    /**
     * @return bool
     */
    protected function isAlreadyOpened(): bool
    {
        return Redis::exists($this->keys->getOpenKey());
    }

    /**
     * @return bool
     */
    protected function hasAlreadyAttempted(): bool
    {
        return Redis::exists($this->keys->getAttemptKey());
    }

    /**
     * @return void
     */
    protected function initializeAttempt(): void
    {
        Redis::setex($this->keys->getAttemptKey(), CircuitEnums::MAX_ATTEMPT_WAIT, self::INITIAL_MAX_ATTEMPT);
        Redis::set($this->keys->getHalfOpenKey(), self::STATUS_VALUE);
    }

    /**
     * @return void
     */
    protected function openCircuit(): void
    {
        Redis::set($this->keys->getOpenKey(), self::STATUS_VALUE);

        event(new CircuitOpened($this->keys->getPrefix()));
    }

    /**
     * @return void
     */
    protected function trackAttempt(): void
    {
        Redis::incrby($this->keys->getAttemptKey(), 1);
    }

    /**
     * @return void
     */
    protected function closeCircuit(): void
    {
        Redis::del($this->keys->getAttemptKey(), $this->keys->getOpenKey(), $this->keys->getHalfOpenKey());
    }
}
