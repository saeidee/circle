<?php

namespace Tests\Unit\Services\CircuitBreaker;

use Tests\TestCase;
use GuzzleHttp\Exception\ServerException;
use App\ValueObjects\CircuitBreaker\CircuitStatus;
use App\Services\Support\RequesterInterface;
use App\Services\CircuitBreaker\CircuitManager;
use App\Services\CircuitBreaker\CircuitTracker;


/**
 * Class CircuitManagerTest
 * @package Tests\Unit\Services\CircuitBreaker
 * @coversDefaultClass \App\Services\CircuitBreaker\CircuitManager
 */
class CircuitManagerTest extends TestCase
{
    const OPEN = 0;
    const CLOSE = 1;
    const HALF_OPEN = 2;
    const MAX_ATTEMPT_REACHED = 3;

    /**
     * @test
     * @covers ::__construct
     * @covers ::makeRequest
     */
    function it_should_return_open_circuit_status_and_not_send_the_request_when_the_circuit_is_open()
    {
        $circuitTracker = $this->createMock(CircuitTracker::class);
        $requester = $this->createMock(RequesterInterface::class);
        $circuitManager = new CircuitManager($circuitTracker, $requester);

        $circuitTracker->expects($this->once())->method('isReachedMaxAttempt')->willReturn(false);
        $circuitTracker->expects($this->once())->method('isOpen')->willReturn(true);
        $requester->expects($this->never())->method('makeRequest');

        $this->assertEquals(new CircuitStatus(self::OPEN), $circuitManager->makeRequest());
    }

    /**
     * @test
     * @covers ::makeRequest
     */
    function it_should_return_max_attempt_reached_circuit_status_and_not_send_the_request_when_the_circuit_is_reached_max_attempt()
    {
        $circuitTracker = $this->createMock(CircuitTracker::class);
        $requester = $this->createMock(RequesterInterface::class);
        $circuitManager = new CircuitManager($circuitTracker, $requester);

        $circuitTracker->expects($this->once())->method('isReachedMaxAttempt')->willReturn(true);
        $requester->expects($this->never())->method('makeRequest');

        $this->assertEquals(new CircuitStatus(self::MAX_ATTEMPT_REACHED), $circuitManager->makeRequest());
    }

    /**
     * @test
     * @covers ::makeRequest
     */
    function it_should_return_close_circuit_status_and_send_the_request()
    {
        $circuitTracker = $this->createMock(CircuitTracker::class);
        $requester = $this->createMock(RequesterInterface::class);
        $circuitManager = new CircuitManager($circuitTracker, $requester);

        $circuitTracker->expects($this->once())->method('isReachedMaxAttempt')->willReturn(false);
        $circuitTracker->expects($this->once())->method('isOpen')->willReturn(false);
        $requester->expects($this->once())->method('makeRequest');
        $circuitTracker->expects($this->once())->method('saveSuccess');

        $this->assertEquals(new CircuitStatus(self::CLOSE), $circuitManager->makeRequest());
    }

    /**
     * @test
     * @covers ::makeRequest
     */
    function it_should_return_open_circuit_status_and_save_the_failure_when_request_exception()
    {
        $circuitTracker = $this->createMock(CircuitTracker::class);
        $requester = $this->createMock(RequesterInterface::class);
        $serverException = $this->createMock(ServerException::class);
        $circuitManager = new CircuitManager($circuitTracker, $requester);

        $circuitTracker->expects($this->once())->method('isReachedMaxAttempt')->willReturn(false);
        $circuitTracker->expects($this->once())->method('isOpen')->willReturn(false);
        $requester->expects($this->once())->method('makeRequest')->willThrowException($serverException);
        $circuitTracker->expects($this->once())->method('saveFailure');

        $this->assertEquals(new CircuitStatus(self::OPEN), $circuitManager->makeRequest());
    }
}
