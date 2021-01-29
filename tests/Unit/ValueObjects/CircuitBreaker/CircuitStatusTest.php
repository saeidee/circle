<?php

namespace Tests\Unit\ValueObjects\CircuitBreaker;

use App\ValueObjects\CircuitBreaker\CircuitStatus;
use Tests\TestCase;

/**
 * Class CircuitStatusTest
 * @package Tests\Unit\ValueObjects\CircuitBreaker
 * @coversDefaultClass \App\ValueObjects\CircuitBreaker\CircuitStatus
 */
class CircuitStatusTest extends TestCase
{
    const OPEN = 0;
    const CLOSE = 1;
    const HALF_OPEN = 2;
    const MAX_ATTEMPT_REACHED = 3;

    /**
     * @test
     * @covers ::__construct
     * @covers ::isOpened
     */
    function it_should_return_true_when_the_circuit_status_is_open_status()
    {
        $this->assertTrue((new CircuitStatus(self::OPEN))->isOpened());
    }

    /**
     * @test
     * @covers ::isOpened
     */
    function it_should_return_false_when_the_circuit_status_is_not_open_status()
    {
        $this->assertFalse((new CircuitStatus(self::CLOSE))->isOpened());
    }

    /**
     * @test
     * @covers ::wasHalfOpen
     */
    function it_should_return_true_when_the_circuit_status_was_half_open()
    {
        $this->assertTrue((new CircuitStatus(self::HALF_OPEN))->wasHalfOpen());
    }

    /**
     * @test
     * @covers ::wasHalfOpen
     */
    function it_should_return_false_when_the_circuit_status_was_not_half_open()
    {
        $this->assertFalse((new CircuitStatus(self::OPEN))->wasHalfOpen());
    }

    /**
     * @test
     * @covers ::isMaxAttemptReached
     */
    function it_should_return_true_when_the_circuit_status_is_max_attempt_reached()
    {
        $this->assertTrue((new CircuitStatus(self::MAX_ATTEMPT_REACHED))->isMaxAttemptReached());
    }

    /**
     * @test
     * @covers ::isMaxAttemptReached
     */
    function it_should_return_false_when_the_circuit_status_is_not_max_attempt_reached()
    {
        $this->assertFalse((new CircuitStatus(self::CLOSE))->isMaxAttemptReached());
    }
}
