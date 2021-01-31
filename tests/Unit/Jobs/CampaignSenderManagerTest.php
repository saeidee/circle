<?php

namespace Tests\Unit\Jobs;

use Tests\TestCase;
use App\Jobs\CampaignSender;
use App\Jobs\CampaignSenderManager;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Queue;
use App\Events\Campaign\CampaignFailed;
use Illuminate\Foundation\Testing\WithFaker;
use App\ValueObjects\Payloads\CampaignPayload;
use App\Repositories\Campaign\CampaignRepositoryInterface;

/**
 * Class CampaignSenderTest
 * @package Tests\Unit\Jobs
 * @coversDefaultClass \App\Jobs\CampaignSenderManager
 */
class CampaignSenderManagerTest extends TestCase
{
    use WithFaker;

    /**
     * @test
     * @covers ::__construct
     * @covers ::handle
     */
    function it_should_handle_campaign_sender()
    {
        Queue::fake();

        $providerName = $this->faker->name;
        $campaignPayload = new CampaignPayload([
            'campaign' => $this->faker->name,
            'content' => ['type' => 'text/html', 'value' => $this->faker->text],
        ]);
        $campaignRepository = $this->createMock(CampaignRepositoryInterface::class);
        $campaignSender = new CampaignSenderManager($providerName, $campaignPayload);

        $campaignRepository
            ->expects($this->once())
            ->method('create')
            ->with([
                'uuid' => $campaignPayload->getId(),
                'name' => $campaignPayload->getCampaign(),
                'type' => $campaignPayload->getContent()->getType(),
                'content' => $campaignPayload->getContent()->getValue(),
            ]);

        $campaignSender->handle($campaignRepository);

        Queue::assertPushed(
            CampaignSender::class,
            function (CampaignSender $campaignSender) use ($providerName, $campaignPayload) {
                $this->assertProperty($campaignSender, 'providerName', $providerName);
                $this->assertProperty($campaignSender, 'campaignPayload', $campaignPayload);

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
        $providerName = $this->faker->name;
        $campaignPayload = new CampaignPayload([]);
        $campaignSender = new CampaignSenderManager($providerName, $campaignPayload);

        $campaignSender->failed();

        Event::assertDispatched(
            CampaignFailed::class,
            function (CampaignFailed $event) use ($providerName, $campaignPayload) {
                $this->assertProperty($event, 'provider', $providerName);
                $this->assertProperty($event, 'uuid', $campaignPayload->getId());

                return true;
            }
        );
    }
}
