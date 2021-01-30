<?php

namespace App\ValueObjects\CircuitBreaker;

use App\Enums\CircuitEnums;

/**
 * Class CircuitStatus
 * @package App\ValueObjects\CircuitBreaker
 */
final class CircuitStatus
{
    /** @var int */
    private $status;

    /**
     * CircuitStatus constructor.
     * @param int $status
     */
    public function __construct(int $status)
    {
        $this->status = $status;
    }

    /**
     * @return bool
     */
    public function isOpened(): bool
    {
        return $this->status === CircuitEnums::OPEN;
    }

    /**
     * @return bool
     */
    public function wasHalfOpen(): bool
    {
        return $this->status === CircuitEnums::HALF_OPEN;
    }

    /**
     * @return bool
     */
    public function isMaxAttemptReached(): bool
    {
        return $this->status === CircuitEnums::MAX_ATTEMPT_REACHED;
    }
}
