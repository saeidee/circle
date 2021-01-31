<?php

namespace Tests\Unit\Jobs;

use App\Events\Campaign\CampaignFailed;
use App\Events\Campaign\CampaignSent;
use App\Factories\CircuitManagerFactory;
use App\Factories\MailProviderFactory;
use App\Jobs\CampaignSender;
use App\Services\CircuitBreaker\CircuitManager;
use App\Services\MailProviders\MailProviderSwitcher;
use App\Services\MailProviders\MailSenderInterface;
use App\ValueObjects\CircuitBreaker\CircuitStatus;
use App\ValueObjects\Payloads\CampaignPayload;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Queue;
use Mockery;
use Tests\TestCase;

/**
 * Class CampaignSenderTest
 * @package Tests\Unit\Jobs
 * @coversDefaultClass \App\Jobs\CampaignSender
 */
class CampaignSenderTest extends TestCase
{
    use WithFaker;

    const OPEN = 0;
    const CLOSE = 1;
    const HALF_OPEN = 2;
    const MAX_ATTEMPT_REACHED = 3;
    const MAX_ATTEMPT_WAIT = Carbon::SECONDS_PER_MINUTE * 5;

    /** @var string */
    private $providerName;
    /** @var CampaignPayload */
    private $campaignPayload;
    /** @var CircuitManagerFactory */
    private $circuitManagerFactory;
    /** @var MailProviderFactory */
    private $mailProviderFactory;
    /** @var MailProviderSwitcher */
    private $senderSwitcher;
    /** @var CampaignSender */
    private $campaignSender;

    /**
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->circuitManagerFactory = $this->createMock(CircuitManagerFactory::class);
        $this->mailProviderFactory = $this->createMock(MailProviderFactory::class);
        $this->senderSwitcher = $this->createMock(MailProviderSwitcher::class);
        $this->campaignPayload = new CampaignPayload([
            'campaign' => $this->faker->name,
            'content' => ['type' => 'text/html', 'value' => $this->faker->text],
        ]);
        $this->providerName = $this->faker->name;
        $this->campaignSender = new CampaignSender($this->providerName, $this->campaignPayload);
    }

    /**
     * @test
     * @covers ::__construct
     * @covers ::handle
     */
    function it_should_handle_campaign_sender_when_circuit_status_is_max_attempt_reached()
    {
        Queue::fake();
        $switchedProvider = $this->faker->name;
        $circuitStatus = new CircuitStatus(self::MAX_ATTEMPT_REACHED);
        $provider = $this->createMock(MailSenderInterface::class);
        $circuitManager = $this->createMock(CircuitManager::class);

        $this->mailProviderFactory
            ->expects($this->once())
            ->method('make')
            ->with($this->providerName, $this->campaignPayload)
            ->willReturn($provider);
        $this->circuitManagerFactory
            ->expects($this->once())
            ->method('make')
            ->with($provider)
            ->willReturn($circuitManager);
        $circuitManager->expects($this->once())->method('makeRequest')->willReturn($circuitStatus);
        $this->senderSwitcher->expects($this->once())->method('switch')->willReturn($switchedProvider);
        Queue::shouldReceive('later')
            ->once()
            ->with(self::MAX_ATTEMPT_WAIT, Mockery::type(CampaignSender::class));

        $this->campaignSender->handle($this->circuitManagerFactory, $this->mailProviderFactory, $this->senderSwitcher);
    }

    /**
     * @test
     * @covers ::handle
     */
    function it_should_handle_campaign_sender_when_circuit_status_is_open()
    {
        Queue::fake();
        $switchedProvider = $this->faker->name;
        $circuitStatus = new CircuitStatus(self::OPEN);
        $provider = $this->createMock(MailSenderInterface::class);
        $circuitManager = $this->createMock(CircuitManager::class);

        $this->mailProviderFactory
            ->expects($this->once())
            ->method('make')
            ->with($this->providerName, $this->campaignPayload)
            ->willReturn($provider);
        $this->circuitManagerFactory
            ->expects($this->once())
            ->method('make')
            ->with($provider)
            ->willReturn($circuitManager);
        $circuitManager->expects($this->once())->method('makeRequest')->willReturn($circuitStatus);
        $this->senderSwitcher->expects($this->once())->method('switch')->willReturn($switchedProvider);

        $this->campaignSender->handle($this->circuitManagerFactory, $this->mailProviderFactory, $this->senderSwitcher);

        Queue::assertPushed(
            CampaignSender::class,
            function (CampaignSender $campaignSender) use ($switchedProvider) {
                $this->assertProperty($campaignSender, 'providerName', $switchedProvider);
                $this->assertProperty($campaignSender, 'campaignPayload', $this->campaignPayload);

                return true;
            }
        );
    }

    /**
     * @test
     * @covers ::handle
     */
    function it_should_handle_campaign_sender_when_circuit_status_is_close()
    {
        Event::fake();
        $circuitStatus = new CircuitStatus(self::CLOSE);
        $provider = $this->createMock(MailSenderInterface::class);
        $circuitManager = $this->createMock(CircuitManager::class);

        $this->mailProviderFactory
            ->expects($this->once())
            ->method('make')
            ->with($this->providerName, $this->campaignPayload)
            ->willReturn($provider);
        $this->circuitManagerFactory
            ->expects($this->once())
            ->method('make')
            ->with($provider)
            ->willReturn($circuitManager);
        $circuitManager->expects($this->once())->method('makeRequest')->willReturn($circuitStatus);

        $this->campaignSender->handle($this->circuitManagerFactory, $this->mailProviderFactory, $this->senderSwitcher);

        Event::assertDispatched(
            CampaignSent::class,
            function (CampaignSent $event) {
                $this->assertProperty($event, 'provider', $this->providerName);
                $this->assertProperty($event, 'uuid', $this->campaignPayload->getId());

                return true;
            }
        );
    }

    /**
     * @test
     * @covers ::failed
     */
    function it_broadcast_campaign_failed_event_when_the_job_failed()
    {
        Event::fake();
        $this->campaignSender->failed();

        Event::assertDispatched(
            CampaignFailed::class,
            function (CampaignFailed $event) {
                $this->assertProperty($event, 'provider', $this->providerName);
                $this->assertProperty($event, 'uuid', $this->campaignPayload->getId());

                return true;
            }
        );
    }
}
