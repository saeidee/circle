<?php

namespace Tests\Unit\Services\CircuitBreaker;

use Tests\TestCase;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Redis;
use App\ValueObjects\CircuitBreaker\CircuitKeys;
use Illuminate\Foundation\Testing\WithFaker;
use App\Services\CircuitBreaker\CircuitTracker;

/**
 * Class CircuitTrackerTest
 * @package Tests\Unit\Services\CircuitBreaker
 * @coversDefaultClass \App\Services\CircuitBreaker\CircuitTracker
 */
class CircuitTrackerTest extends TestCase
{
    const STATUS_VALUE = 1;
    const MAX_ATTEMPTS = 3;
    const INITIAL_MAX_ATTEMPT = 1;
    const OPEN_KEY = ':circuit:open';
    const ATTEMPT_KEY = ':circuit:attempts';
    const OPEN_TTL = Carbon::SECONDS_PER_MINUTE;
    const MAX_ATTEMPTS_TTL = Carbon::SECONDS_PER_MINUTE * 5;

    use WithFaker;

    /**
     * @test
     * @covers ::__construct
     * @covers ::isOpen
     */
    function it_should_return_circuit_open_status()
    {
        $status = $this->faker->boolean;
        $prefix = $this->faker->text(5);
        $circuitKeys = new CircuitKeys($prefix);
        $circuitTracker = new CircuitTracker($circuitKeys);

        Redis::shouldReceive('exists')->once()->with($prefix . self::OPEN_KEY)->andReturn($status);

        $this->assertEquals($status, $circuitTracker->isOpen());
    }

    /**
     * @test
     * @covers ::isReachedMaxAttempt
     */
    function it_should_return_true_when_circuit_is_reached_max_attempts()
    {
        $maxAttempt = $this->faker->numberBetween(4, 100);
        $circuitTracker = $this->getMockBuilder(CircuitTracker::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['getAttempts'])
            ->getMock();

        $circuitTracker->expects($this->once())->method('getAttempts')->willReturn($maxAttempt);

        $this->assertTrue($circuitTracker->isReachedMaxAttempt());
    }

    /**
     * @test
     * @covers ::isReachedMaxAttempt
     */
    function it_should_return_false_when_circuit_is_not_reached_max_attempts()
    {
        $maxAttempt = $this->faker->numberBetween(1, 3);
        $circuitTracker = $this->getMockBuilder(CircuitTracker::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['getAttempts'])
            ->getMock();

        $circuitTracker->expects($this->once())->method('getAttempts')->willReturn($maxAttempt);

        $this->assertFalse($circuitTracker->isReachedMaxAttempt());
    }

    /**
     * @test
     * @covers ::wasHalfOpen
     */
    function it_should_return_ture_when_circuit_was_half_open()
    {
        $maxAttempt = $this->faker->numberBetween(2, 10);
        $circuitTracker = $this->getMockBuilder(CircuitTracker::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['getAttempts'])
            ->getMock();

        $circuitTracker->expects($this->once())->method('getAttempts')->willReturn($maxAttempt);

        $this->assertTrue($circuitTracker->wasHalfOpen());
    }

    /**
     * @test
     * @covers ::wasHalfOpen
     */
    function it_should_return_false_when_circuit_was_not_half_open()
    {
        $maxAttempt = 0;
        $circuitTracker = $this->getMockBuilder(CircuitTracker::class)
            ->disableOriginalConstructor()
            ->onlyMethods(['getAttempts'])
            ->getMock();

        $circuitTracker->expects($this->once())->method('getAttempts')->willReturn($maxAttempt);

        $this->assertFalse($circuitTracker->wasHalfOpen());
    }

    /**
     * @test
     * @covers ::saveFailure
     */
    function it_should_save_circuit_failure()
    {
        $prefix = $this->faker->text(5);
        $circuitKeys = new CircuitKeys($prefix);
        $circuitTracker = new CircuitTracker($circuitKeys);

        Redis::shouldReceive('setex')
            ->once()
            ->with($prefix . self::OPEN_KEY, self::STATUS_VALUE, self::OPEN_TTL);
        Redis::shouldReceive('exists')->once()->with($prefix . self::ATTEMPT_KEY)->andReturn(true);
        Redis::shouldReceive('incrby')->once()->with($prefix . self::ATTEMPT_KEY, 1);

        $circuitTracker->saveFailure();
    }

    /**
     * @test
     * @covers ::saveFailure
     */
    function it_should_save_circuit_failure_when_it_was_first_failure()
    {
        $prefix = $this->faker->text(5);
        $circuitKeys = new CircuitKeys($prefix);
        $circuitTracker = new CircuitTracker($circuitKeys);

        Redis::shouldReceive('setex')
            ->once()
            ->with($prefix . self::OPEN_KEY, self::STATUS_VALUE, self::OPEN_TTL);
        Redis::shouldReceive('exists')->once()->with($prefix . self::ATTEMPT_KEY)->andReturn(false);
        Redis::shouldReceive('setex')
            ->once()
            ->with($prefix . self::ATTEMPT_KEY, self::MAX_ATTEMPTS_TTL, self::INITIAL_MAX_ATTEMPT);

        $circuitTracker->saveFailure();
    }

    /**
     * @test
     * @covers ::saveSuccess
     */
    function it_should_save_success_circuit_status()
    {
        $prefix = $this->faker->text(5);
        $circuitKeys = new CircuitKeys($prefix);
        $circuitTracker = new CircuitTracker($circuitKeys);

        Redis::shouldReceive('del')->once()->with($prefix . self::ATTEMPT_KEY);

        $circuitTracker->saveSuccess();
    }

    /**
     * @test
     * @covers ::getAttempts
     */
    function it_should_return_attempts()
    {
        $prefix = $this->faker->text(5);
        $circuitKeys = new CircuitKeys($prefix);
        $circuitTracker = new CircuitTracker($circuitKeys);

        Redis::shouldReceive('get')->once()->with($prefix . self::ATTEMPT_KEY)->andReturn('4');

        $this->assertEquals(4, $this->invokeMethod($circuitTracker, 'getAttempts'));
    }
}
