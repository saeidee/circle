<?php

namespace Tests\Unit\Services\CircuitBreaker;

use Tests\TestCase;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Event;
use App\Events\CircuitBreaker\CircuitClosed;
use App\Events\CircuitBreaker\CircuitOpened;
use Illuminate\Foundation\Testing\WithFaker;
use App\ValueObjects\CircuitBreaker\CircuitKeys;
use App\Services\CircuitBreaker\CircuitTracker;
use App\Events\CircuitBreaker\CircuitMaxAttemptReached;

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
    const HALF_OPEN_KEY = ':circuit:half-open';
    const MAX_ATTEMPT_WAIT = Carbon::SECONDS_PER_MINUTE * 5;

    use WithFaker;

    /**
     * @test
     * @covers ::__construct
     * @covers ::isOpen
     */
    function it_should_return_true_when_the_circuit_is_open()
    {
        $prefix = $this->faker->text(5);
        $circuitKeys = new CircuitKeys($prefix);
        $circuitTracker = $this->getMockBuilder(CircuitTracker::class)
            ->setConstructorArgs([$circuitKeys])
            ->onlyMethods(['isReachedMaxAttempt'])
            ->getMock();

        Redis::shouldReceive('exists')->once()->with($prefix . self::OPEN_KEY)->andReturn(true);
        $circuitTracker->expects($this->once())->method('isReachedMaxAttempt')->willReturn(true);

        $this->assertTrue($circuitTracker->isOpen());
    }

    /**
     * @test
     * @covers ::isOpen
     */
    function it_should_return_false_when_the_circuit_is_not_open()
    {
        $prefix = $this->faker->text(5);
        $circuitKeys = new CircuitKeys($prefix);
        $circuitTracker = $this->getMockBuilder(CircuitTracker::class)
            ->setConstructorArgs([$circuitKeys])
            ->onlyMethods(['isReachedMaxAttempt'])
            ->getMock();

        Redis::shouldReceive('exists')->once()->with($prefix . self::OPEN_KEY)->andReturn(true);
        $circuitTracker->expects($this->once())->method('isReachedMaxAttempt')->willReturn(false);

        $this->assertFalse($circuitTracker->isOpen());
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
    function it_should_return_circuit_was_half_open_status()
    {
        $status = $this->faker->boolean;
        $prefix = $this->faker->text(5);
        $circuitKeys = new CircuitKeys($prefix);
        $circuitTracker = new CircuitTracker($circuitKeys);

        Redis::shouldReceive('exists')->once()->with($prefix . self::HALF_OPEN_KEY)->andReturn($status);

        $this->assertEquals($status, $circuitTracker->wasHalfOpen());
    }

    /**
     * @test
     * @covers ::saveFailure
     */
    function it_should_save_circuit_failure_when_it_was_first_failure()
    {
        $prefix = $this->faker->text(5);
        $circuitKeys = new CircuitKeys($prefix);
        $circuitTracker = $this->getMockBuilder(CircuitTracker::class)
            ->setConstructorArgs([$circuitKeys])
            ->onlyMethods(['isAlreadyOpened', 'openCircuit'])
            ->getMock();

        $circuitTracker->expects($this->once())->method('isAlreadyOpened')->willReturn(false);
        $circuitTracker->expects($this->once())->method('openCircuit');

        $circuitTracker->saveFailure();
    }

    /**
     * @test
     * @covers ::saveFailure
     */
    function it_should_save_circuit_failure_when_it_has_already_attempt()
    {
        $prefix = $this->faker->text(5);
        $circuitKeys = new CircuitKeys($prefix);
        $circuitTracker = $this->getMockBuilder(CircuitTracker::class)
            ->setConstructorArgs([$circuitKeys])
            ->onlyMethods(['isAlreadyOpened', 'hasAlreadyAttempted', 'initializeAttempt'])
            ->getMock();

        $circuitTracker->expects($this->once())->method('isAlreadyOpened')->willReturn(true);
        $circuitTracker->expects($this->once())->method('hasAlreadyAttempted')->willReturn(false);
        $circuitTracker->expects($this->once())->method('initializeAttempt');

        $circuitTracker->saveFailure();
    }

    /**
     * @test
     * @covers ::saveFailure
     */
    function it_should_save_circuit_failure_and_track_attempt()
    {
        Event::fake();
        $prefix = $this->faker->text(5);
        $circuitKeys = new CircuitKeys($prefix);
        $circuitTracker = $this->getMockBuilder(CircuitTracker::class)
            ->setConstructorArgs([$circuitKeys])
            ->onlyMethods(['isAlreadyOpened', 'hasAlreadyAttempted', 'trackAttempt', 'isReachedMaxAttempt'])
            ->getMock();

        $circuitTracker->expects($this->once())->method('isAlreadyOpened')->willReturn(true);
        $circuitTracker->expects($this->once())->method('hasAlreadyAttempted')->willReturn(true);
        $circuitTracker->expects($this->once())->method('trackAttempt');
        $circuitTracker->expects($this->once())->method('isReachedMaxAttempt')->willReturn(true);

        $circuitTracker->saveFailure();

        Event::assertDispatched(
            CircuitMaxAttemptReached::class,
            function (CircuitMaxAttemptReached $event) use ($prefix) {
                $this->assertProperty($event, 'circuitName', $prefix);

                return true;
            }
        );
    }

    /**
     * @test
     * @covers ::saveSuccess
     */
    function it_should_save_success_circuit_status()
    {
        Event::fake();
        $prefix = $this->faker->text(5);
        $circuitKeys = new CircuitKeys($prefix);
        $circuitTracker = $this->getMockBuilder(CircuitTracker::class)
            ->setConstructorArgs([$circuitKeys])
            ->onlyMethods(['wasHalfOpen'])
            ->getMock();

        $circuitTracker->expects($this->once())->method('wasHalfOpen')->willReturn(true);
        Redis::shouldReceive('del')
            ->once()
            ->with($prefix . self::ATTEMPT_KEY, $prefix . self::OPEN_KEY, $prefix . self::HALF_OPEN_KEY);

        $circuitTracker->saveSuccess();

        Event::assertDispatched(
            CircuitClosed::class,
            function (CircuitClosed $event) use ($prefix) {
                $this->assertProperty($event, 'circuitName', $prefix);

                return true;
            }
        );
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

    /**
     * @test
     * @covers ::isAlreadyOpened
     */
    function it_should_return_already_opened_status()
    {
        $status = $this->faker->boolean;
        $prefix = $this->faker->text(5);
        $circuitKeys = new CircuitKeys($prefix);
        $circuitTracker = new CircuitTracker($circuitKeys);

        Redis::shouldReceive('exists')->once()->with($prefix . self::OPEN_KEY)->andReturn($status);

        $this->assertEquals($status, $this->invokeMethod($circuitTracker, 'isAlreadyOpened'));
    }

    /**
     * @test
     * @covers ::hasAlreadyAttempted
     */
    function it_should_return_has_already_attempted_status()
    {
        $status = $this->faker->boolean;
        $prefix = $this->faker->text(5);
        $circuitKeys = new CircuitKeys($prefix);
        $circuitTracker = new CircuitTracker($circuitKeys);

        Redis::shouldReceive('exists')->once()->with($prefix . self::ATTEMPT_KEY)->andReturn($status);

        $this->assertEquals($status, $this->invokeMethod($circuitTracker, 'hasAlreadyAttempted'));
    }

    /**
     * @test
     * @covers ::initializeAttempt
     */
    function it_should_initialize_attempt()
    {
        $prefix = $this->faker->text(5);
        $circuitKeys = new CircuitKeys($prefix);
        $circuitTracker = new CircuitTracker($circuitKeys);

        Redis::shouldReceive('setex')
            ->once()
            ->with($prefix . self::ATTEMPT_KEY, self::MAX_ATTEMPT_WAIT, self::INITIAL_MAX_ATTEMPT);
        Redis::shouldReceive('set')
            ->once()
            ->with($prefix . self::HALF_OPEN_KEY, self::STATUS_VALUE);

        $this->invokeMethod($circuitTracker, 'initializeAttempt');
    }

    /**
     * @test
     * @covers ::openCircuit
     */
    function it_should_open_circuit()
    {
        Event::fake();
        $prefix = $this->faker->text(5);
        $circuitKeys = new CircuitKeys($prefix);
        $circuitTracker = new CircuitTracker($circuitKeys);

        Redis::shouldReceive('set')->once()->with($prefix . self::OPEN_KEY, self::STATUS_VALUE);

        $this->invokeMethod($circuitTracker, 'openCircuit');

        Event::assertDispatched(
            CircuitOpened::class,
            function (CircuitOpened $event) use ($prefix) {
                $this->assertProperty($event, 'circuitName', $prefix);

                return true;
            }
        );
    }

    /**
     * @test
     * @covers ::trackAttempt
     */
    function it_should_track_attempt()
    {
        $prefix = $this->faker->text(5);
        $circuitKeys = new CircuitKeys($prefix);
        $circuitTracker = new CircuitTracker($circuitKeys);

        Redis::shouldReceive('incrby')->once()->with($prefix . self::ATTEMPT_KEY, 1);

        $this->invokeMethod($circuitTracker, 'trackAttempt');
    }

    /**
     * @test
     * @covers ::closeCircuit
     */
    function it_should_close_circuit()
    {
        $prefix = $this->faker->text(5);
        $circuitKeys = new CircuitKeys($prefix);
        $circuitTracker = new CircuitTracker($circuitKeys);

        Redis::shouldReceive('del')
            ->once()
            ->with($prefix . self::ATTEMPT_KEY, $prefix . self::OPEN_KEY, $prefix . self::HALF_OPEN_KEY);

        $this->invokeMethod($circuitTracker, 'closeCircuit');
    }
}
