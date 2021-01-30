<?php

namespace Tests\Unit\ValueObjects\CircuitBreaker;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use App\ValueObjects\CircuitBreaker\CircuitKeys;

/**
 * Class CircuitKeysTest
 * @package Tests\Unit\ValueObjects\CircuitBreaker
 * @coversDefaultClass \App\ValueObjects\CircuitBreaker\CircuitKeys
 */
class CircuitKeysTest extends TestCase
{
    use WithFaker;

    const OPEN_KEY = ':circuit:open';
    const ATTEMPT_KEY = ':circuit:attempts';
    const HALF_OPEN_KEY = ':circuit:half-open';

    /**
     * @test
     * @covers ::__construct
     * @covers ::getOpenKey
     */
    function it_should_return_open_key()
    {
        $prefix = $this->faker->text(10);
        $circuitKeys = new CircuitKeys($prefix);

        $this->assertEquals($prefix . self::OPEN_KEY, $circuitKeys->getOpenKey());
    }

    /**
     * @test
     * @covers ::getHalfOpenKey
     */
    function it_should_return_half_open_key()
    {
        $prefix = $this->faker->text(10);
        $circuitKeys = new CircuitKeys($prefix);

        $this->assertEquals($prefix . self::HALF_OPEN_KEY, $circuitKeys->getHalfOpenKey());
    }

    /**
     * @test
     * @covers ::getAttemptKey
     */
    function it_should_return_attempt_key()
    {
        $prefix = $this->faker->text(10);
        $circuitKeys = new CircuitKeys($prefix);

        $this->assertEquals($prefix . self::ATTEMPT_KEY, $circuitKeys->getAttemptKey());
    }

    /**
     * @test
     * @covers ::getPrefix
     */
    function it_should_return_prefix()
    {
        $prefix = $this->faker->text(10);
        $circuitKeys = new CircuitKeys($prefix);

        $this->assertEquals($prefix, $circuitKeys->getPrefix());
    }
}
