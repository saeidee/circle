<?php

namespace Tests\Unit\Events\CircuitBreaker;

use Tests\TestCase;
use App\Events\CircuitBreaker\CircuitOpened;
use Illuminate\Foundation\Testing\WithFaker;

/**
 * Class CircuitOpenedTest
 * @package Tests\Unit\Events\CircuitBreaker
 * @coversDefaultClass \App\Events\CircuitBreaker\CircuitOpened
 */
class CircuitOpenedTest extends TestCase
{
    use WithFaker;

    const TYPE = 'OPENED';

    /**
     * @test
     * @covers ::getType
     */
    function it_should_return_type()
    {
        $circuitName = $this->faker->name;
        $circuitOpened = new CircuitOpened($circuitName);

        $this->assertEquals(self::TYPE, $circuitOpened->getType());
    }
}
