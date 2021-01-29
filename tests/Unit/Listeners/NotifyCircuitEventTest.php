<?php

namespace Tests\Unit\Listeners;

use Tests\TestCase;
use App\Listeners\NotifyCircuitEvent;
use App\Notifications\CircuitStateChanged;
use App\Events\CircuitBreaker\AbstractCircuitEvent;

/**
 * Class NotifyCircuitEventTest
 * @package Tests\Unit\Listeners
 * @coversDefaultClass \App\Listeners\NotifyCircuitEvent
 */
class NotifyCircuitEventTest extends TestCase
{
    /**
     * @test
     * @covers ::handle
     */
    function it_should_handle_notifying_circuit_event()
    {
        $circuitBreakerEvent = $this->createMock(AbstractCircuitEvent::class);
        $notifyCircuitEvent = $this->getMockBuilder(NotifyCircuitEvent::class)->onlyMethods(['notify'])->getMock();

        $notifyCircuitEvent
            ->expects($this->once())
            ->method('notify')
            ->with(new CircuitStateChanged($circuitBreakerEvent));

        $notifyCircuitEvent->handle($circuitBreakerEvent);
    }

    /**
     * @test
     * @covers ::routeNotificationForSlack
     */
    function it_should_slack_endpoint()
    {
        $notifyCircuitEvent = $this->getMockBuilder(NotifyCircuitEvent::class)->onlyMethods(['notify'])->getMock();

        $this->assertEquals(config('services.slack.endpoint'), $notifyCircuitEvent->routeNotificationForSlack());
    }
}
