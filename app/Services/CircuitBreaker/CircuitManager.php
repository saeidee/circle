<?php

namespace App\Services\CircuitBreaker;

use App\Enums\CircuitEnums;
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
        if ($this->circuitTracker->isReachedMaxAttempt()) {
            return new CircuitStatus(CircuitEnums::MAX_ATTEMPT_REACHED);
        }

        if ($this->circuitTracker->isOpen()) {
            return new CircuitStatus(CircuitEnums::OPEN);
        }

        try {
            $this->requester->makeRequest();

            $this->circuitTracker->saveSuccess();
        } catch (GuzzleException $exception) {
            $this->circuitTracker->saveFailure();

            return new CircuitStatus(CircuitEnums::OPEN);
        }

        return new CircuitStatus(CircuitEnums::CLOSE);
    }
}
