<?php

namespace Tests\Unit\Listeners;

use Tests\TestCase;
use App\Models\Campaign;
use App\Events\CampaignSent;
use App\Listeners\LogCampaignSent;
use Illuminate\Foundation\Testing\WithFaker;
use App\Repositories\Campaign\CampaignRepositoryInterface;

/**
 * Class LogCampaignSentTest
 * @package Tests\Unit\Listeners
 * @coversDefaultClass \App\Listeners\LogCampaignSent
 */
class LogCampaignSentTest extends TestCase
{
    use WithFaker;

    /**
     * @test
     * @covers ::__construct
     * @covers ::handle
     */
    function it_should_log_campaign_sent_test()
    {
        $uuid = $this->faker->uuid;
        $provider = $this->faker->name;
        $campaign = $this->createMock(Campaign::class);
        $campaignSent = $this->createMock(CampaignSent::class);
        $campaignRepository = $this->createMock(CampaignRepositoryInterface::class);
        $logCampaignSent = new LogCampaignSent($campaignRepository);

        $campaignSent->expects($this->once())->method('getUuid')->willReturn($uuid);
        $campaignRepository->expects($this->once())->method('findByUuid')->with($uuid)->willReturn($campaign);
        $campaignSent->expects($this->once())->method('getProvider')->willReturn($provider);
        $campaign->expects($this->once())->method('markAsSent')->with($provider);

        $logCampaignSent->handle($campaignSent);
    }
}
