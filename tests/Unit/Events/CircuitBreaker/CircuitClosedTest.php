<?php

namespace Tests\Unit\Events\CircuitBreaker;

use Tests\TestCase;
use App\Events\CircuitBreaker\CircuitClosed;
use Illuminate\Foundation\Testing\WithFaker;

/**
 * Class CircuitClosedTest
 * @package Tests\Unit\Events\CircuitBreaker
 * @coversDefaultClass \App\Events\CircuitBreaker\CircuitClosed
 */
class CircuitClosedTest extends TestCase
{
    use WithFaker;

    const TYPE = 'CLOSED';

    /**
     * @test
     * @covers ::getType
     */
    function it_should_return_type()
    {
        $circuitName = $this->faker->name;
        $circuitClosed = new CircuitClosed($circuitName);

        $this->assertEquals(self::TYPE, $circuitClosed->getType());
    }
}
