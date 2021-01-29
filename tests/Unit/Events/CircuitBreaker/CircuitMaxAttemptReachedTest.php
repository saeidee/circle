<?php

namespace Tests\Unit\Events\CircuitBreaker;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use App\Events\CircuitBreaker\CircuitMaxAttemptReached;

/**
 * Class CircuitMaxAttemptReachedTest
 * @package Tests\Unit\Events\CircuitBreaker
 * @coversDefaultClass \App\Events\CircuitBreaker\CircuitMaxAttemptReached
 */
class CircuitMaxAttemptReachedTest extends TestCase
{
    use WithFaker;

    const TYPE = 'MAX ATTEMPT';

    /**
     * @test
     * @covers ::getType
     */
    function it_should_return_type()
    {
        $circuitName = $this->faker->name;
        $circuitOpened = new CircuitMaxAttemptReached($circuitName);

        $this->assertEquals(self::TYPE, $circuitOpened->getType());
    }
}
