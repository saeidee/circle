<?php

namespace Tests\Unit\Factories;

use Tests\TestCase;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Request;
use App\Factories\RequestFactory;
use App\Services\Support\Requester;
use App\Factories\CircuitManagerFactory;
use App\Factories\CircuitTrackerFactory;
use Illuminate\Foundation\Testing\WithFaker;
use App\Services\CircuitBreaker\CircuitManager;
use App\Services\CircuitBreaker\CircuitTracker;
use App\Services\MailProviders\MailSenderInterface;

/**
 * Class CircuitManagerFactoryTest
 * @package Tests\Unit\Factories
 * @coversDefaultClass \App\Factories\CircuitManagerFactory
 */
class CircuitManagerFactoryTest extends TestCase
{
    use WithFaker;

    /**
     * @test
     * @covers ::__construct
     * @covers ::make
     */
    function it_should_make_circuit_manager_instance()
    {
        $prefix = $this->faker->text(10);
        $client = $this->createMock(Client::class);
        $mailSender = $this->createMock(MailSenderInterface::class);
        $requestFactory = $this->createMock(RequestFactory::class);
        $circuitTrackerFactory = $this->createMock(CircuitTrackerFactory::class);
        $circuitTracker = $this->createMock(CircuitTracker::class);
        $request = $this->createMock(Request::class);
        $requester = new Requester($client, $request);

        $requestFactory->expects($this->once())->method('make')->with($mailSender)->willReturn($request);
        $circuitTrackerFactory->expects($this->once())->method('make')->with($prefix)->willReturn($circuitTracker);
        $mailSender->expects($this->once())->method('getCircuitPrefix')->willReturn($prefix);

        $this->assertEquals(
            new CircuitManager($circuitTracker, $requester),
            (new CircuitManagerFactory($client, $requestFactory, $circuitTrackerFactory))->make($mailSender)
        );
    }
}
