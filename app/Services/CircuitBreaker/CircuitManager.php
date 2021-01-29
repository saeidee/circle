<?php

namespace App\Services\CircuitBreaker;

use App\Enums\CircuitStatusEnums;
use GuzzleHttp\Exception\GuzzleException;
use App\Services\Support\RequesterInterface;
use App\ValueObjects\CircuitBreaker\CircuitStatus;

/**
 * Class CircuitManager
 * @package App\Services\CircuitBreaker
 */
class CircuitManager
{
    /** @var $requester */
    private $requester;
    /** @var CircuitTracker */
    private $circuitTracker;

    /**
     * CircuitManager constructor.
     * @param CircuitTracker $circuitTracker
     * @param RequesterInterface $requester
     */
    public function __construct(CircuitTracker $circuitTracker, RequesterInterface $requester)
    {
        $this->requester = $requester;
        $this->circuitTracker = $circuitTracker;
    }

    /**
     * @return CircuitStatus
     */
    public function makeRequest(): CircuitStatus
    {
        if ($this->circuitTracker->isOpen()) {
            return new CircuitStatus(CircuitStatusEnums::OPEN);
        }

        if ($this->circuitTracker->isReachedMaxAttempt()) {
            return new CircuitStatus(CircuitStatusEnums::MAX_ATTEMPT_REACHED);
        }

        try {
            $this->requester->makeRequest();

            if ($this->circuitTracker->wasHalfOpen()) {
                $this->circuitTracker->saveSuccess();

                return new CircuitStatus(CircuitStatusEnums::HALF_OPEN);
            }
        } catch (GuzzleException $exception) {
            $this->circuitTracker->saveFailure();

            return new CircuitStatus(CircuitStatusEnums::OPEN);
        }

        return new CircuitStatus(CircuitStatusEnums::CLOSE);
    }
}
