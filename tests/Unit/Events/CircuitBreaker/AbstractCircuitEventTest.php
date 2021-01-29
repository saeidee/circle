<?php

namespace Tests\Unit\Events\CircuitBreaker;

use Tests\TestCase;
use Tests\Fakes\FakeAbstractCircuitEvent;
use Illuminate\Foundation\Testing\WithFaker;

/**
 * Class AbstractCircuitEventTest
 * @package Tests\Unit\Events\CircuitBreaker
 * @coversDefaultClass \App\Events\CircuitBreaker\AbstractCircuitEvent
 */
class AbstractCircuitEventTest extends TestCase
{
    use WithFaker;

    /**
     * @test
     * @covers ::__construct
     * @covers ::getMessage
     */
    function it_should_return_message()
    {
        $type = $this->faker->name;
        $circuitName = $this->faker->name;
        $abstractCircuitEvent = $this->getMockBuilder(FakeAbstractCircuitEvent::class)
            ->setConstructorArgs([$circuitName])
            ->onlyMethods(['getType'])
            ->getMock();

        $abstractCircuitEvent->expects($this->once())->method('getType')->willReturn($type);

        $this->assertEquals("{$circuitName} circuit is now {$type}", $abstractCircuitEvent->getMessage());
    }
}
