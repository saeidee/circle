<?php

namespace Tests\Unit\Listeners;

use Tests\TestCase;
use App\Models\Campaign;
use App\Events\CampaignFailed;
use App\Listeners\LogCampaignFailed;
use Illuminate\Foundation\Testing\WithFaker;
use App\Repositories\Campaign\CampaignRepositoryInterface;

/**
 * Class LogCampaignFailedTest
 * @package Tests\Unit\Listeners
 * @coversDefaultClass \App\Listeners\LogCampaignFailed
 */
class LogCampaignFailedTest extends TestCase
{
    use WithFaker;

    /**
     * @test
     * @covers ::__construct
     * @covers ::handle
     */
    function it_should_log_campaign_failed_test()
    {
        $uuid = $this->faker->uuid;
        $provider = $this->faker->name;
        $campaign = $this->createMock(Campaign::class);
        $campaignFailed = $this->createMock(CampaignFailed::class);
        $campaignRepository = $this->createMock(CampaignRepositoryInterface::class);
        $logCampaignFailed = new LogCampaignFailed($campaignRepository);

        $campaignFailed->expects($this->once())->method('getUuid')->willReturn($uuid);
        $campaignRepository->expects($this->once())->method('findByUuid')->with($uuid)->willReturn($campaign);
        $campaignFailed->expects($this->once())->method('getProvider')->willReturn($provider);
        $campaign->expects($this->once())->method('markAsFailed')->with($provider);

        $logCampaignFailed->handle($campaignFailed);
    }
}
