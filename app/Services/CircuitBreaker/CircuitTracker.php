<?php

namespace App\Services\CircuitBreaker;

use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Redis;
use App\ValueObjects\CircuitBreaker\CircuitKeys;

/**
 * Class CircuitTracker
 * @package App\Services\CircuitBreaker
 */
class CircuitTracker
{
    const STATUS_VALUE = 1;
    const MAX_ATTEMPTS = 3;
    const INITIAL_MAX_ATTEMPT = 1;
    const OPEN_TTL = Carbon::SECONDS_PER_MINUTE;
    const MAX_ATTEMPTS_TTL = Carbon::SECONDS_PER_MINUTE * 5;

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
        return Redis::exists($this->keys->getOpenKey());
    }

    /**
     * @return bool
     */
    public function wasHalfOpen(): bool
    {
        return $this->getAttempts() > self::INITIAL_MAX_ATTEMPT;
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
        Redis::setex($this->keys->getOpenKey(), self::STATUS_VALUE, self::OPEN_TTL);

        if (!Redis::exists($this->keys->getAttemptKey())) {
            Redis::setex($this->keys->getAttemptKey(), self::MAX_ATTEMPTS_TTL, self::INITIAL_MAX_ATTEMPT);

            return;
        }

        Redis::incrby($this->keys->getAttemptKey(), 1);
    }

    /*
     * @return void
     */
    public function saveSuccess(): void
    {
        Redis::del($this->keys->getAttemptKey());
    }

    /**
     * @return int
     */
    protected function getAttempts(): int
    {
        return (int)Redis::get($this->keys->getAttemptKey());
    }
}
