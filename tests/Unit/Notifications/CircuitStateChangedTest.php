<?php

namespace Tests\Units\Notifications;

use Tests\TestCase;
use App\Notifications\CircuitStateChanged;
use Illuminate\Foundation\Testing\WithFaker;
use PHPUnit\Framework\MockObject\MockObject;
use App\Events\CircuitBreaker\AbstractCircuitEvent;
use Illuminate\Notifications\Messages\SlackMessage;

/**
 * Class CircuitStateChangedTest
 * @package Tests\Units\Notifications
 * @coversDefaultClass \App\Notifications\CircuitStateChanged
 */
class CircuitStateChangedTest extends TestCase
{
    use WithFaker;

    /**
     * @var AbstractCircuitEvent|MockObject
     */
    private $circuitBreakerEvent;

    public function setUp(): void
    {
        parent::setUp();

        $this->circuitBreakerEvent = $this->createMock(AbstractCircuitEvent::class);
    }

    /**
     * @test
     * @covers ::__construct
     * @covers ::via
     */
    function it_should_return_channel_type()
    {
        $circuitStateChanged = new CircuitStateChanged($this->circuitBreakerEvent);

        $this->assertSame(['slack'], $circuitStateChanged->via());
    }

    /**
     * @test
     * @covers ::toSlack
     */
    function it_should_equal_with_to_slack()
    {
        $content = $this->faker->text;
        $slackMessage = $this->createMock(SlackMessage::class);
        /** @var CircuitStateChanged|MockObject $circuitStateChanged */
        $circuitStateChanged = $this->getMockBuilder(CircuitStateChanged::class)
            ->setConstructorArgs([$this->circuitBreakerEvent])
            ->onlyMethods(['getSlackMessageInstance'])
            ->getMock();

        $slackMessage->expects($this->once())->method('from')->with('Circle', ':traffic_light:')->willReturnSelf();
        $slackMessage->expects($this->once())->method('to')->with('circuit-breaker-channel')->willReturnSelf();
        $slackMessage->expects($this->once())->method('warning')->willReturnSelf();
        $slackMessage->expects($this->once())->method('content')->with($content)->willReturnSelf();
        $this->circuitBreakerEvent->expects($this->once())->method('getMessage')->willReturn($content);
        $circuitStateChanged->expects($this->once())->method('getSlackMessageInstance')->willReturn($slackMessage);

        $this->assertEquals($slackMessage, $circuitStateChanged->toSlack());
    }

    /**
     * @test
     * @covers ::getSlackMessageInstance
     */
    function it_should_return_slack_message_instance()
    {
        $circuitStateChanged = new CircuitStateChanged($this->circuitBreakerEvent);

        $this->assertInstanceOf(
            SlackMessage::class,
            $this->invokeMethod($circuitStateChanged, 'getSlackMessageInstance')
        );
    }
}
