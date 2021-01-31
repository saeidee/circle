<?php

namespace Tests\Unit\Repositories\Campaign;

use Illuminate\Database\Query\Builder;
use Tests\TestCase;
use App\Models\Campaign;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Pagination\LengthAwarePaginator;
use App\Repositories\Campaign\CampaignRepository;

/**
 * Class CampaignRepositoryTest
 * @package Tests\Unit\Repositories\CampaignResource
 * @coversDefaultClass \App\Repositories\Campaign\CampaignRepository
 */
class CampaignRepositoryTest extends TestCase
{
    use WithFaker;

    /** @var Campaign */
    private $campaign;
    /** @var CampaignRepository */
    private $campaignRepository;

    /**
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->campaign = $this->getMockBuilder(Campaign::class)
            ->addMethods(['paginate', 'create', 'where', 'queued', 'sent', 'failed'])
            ->getMock();
        $this->campaignRepository = new CampaignRepository($this->campaign);
    }

    /**
     * @test
     * @covers ::__construct
     * @covers ::paginate
     */
    function it_should_paginate_all_campaigns()
    {
        $prePage = $this->faker->randomDigitNotNull;
        $lengthAwarePaginator = $this->createMock(LengthAwarePaginator::class);

        $this->campaign->expects($this->once())->method('paginate')->with($prePage)->willReturn($lengthAwarePaginator);

        $this->assertEquals($lengthAwarePaginator, $this->campaignRepository->paginate($prePage));
    }

    /**
     * @test
     * @covers ::create
     */
    function it_should_create_new_campaign()
    {
        $fields = [$this->faker->name => $this->faker->name];

        $this->campaign->expects($this->once())->method('create')->with($fields);

        $this->campaignRepository->create($fields);
    }

    /**
     * @test
     * @covers ::findByUuid
     */
    function it_should_find_campaign_by_uuid()
    {
        $campaign = new Campaign();
        $uuid = $this->faker->uuid;

        $this->campaign
            ->expects($this->once())
            ->method('where')
            ->with('uuid', $uuid)
            ->willReturn(collect([$campaign]));

        $this->assertEquals($campaign, $this->campaignRepository->findByUuid($uuid));
    }

    /**
     * @test
     * @covers ::findByUuid
     */
    function it_should_return_null_when_the_campaign_not_exist_with_the_given_uuid()
    {
        $uuid = $this->faker->uuid;

        $this->campaign
            ->expects($this->once())
            ->method('where')
            ->with('uuid', $uuid)
            ->willReturn(collect([null]));

        $this->assertNull($this->campaignRepository->findByUuid($uuid));
    }

    /**
     * @test
     * @covers ::getQueuedCount
     */
    function it_should_return_queued_campaigns_count()
    {
        $queuedCampaigns = $this->faker->randomDigitNotNull;
        $builder = $this->createMock(Builder::class);

        $this->campaign->expects($this->once())->method('queued')->willReturn($builder);
        $builder->expects($this->once())->method('count')->willReturn($queuedCampaigns);

        $this->assertEquals($queuedCampaigns, $this->campaignRepository->getQueuedCount());
    }

    /**
     * @test
     * @covers ::getSentCount
     */
    function it_should_return_sent_campaigns_count()
    {
        $sentCampaigns = $this->faker->randomDigitNotNull;
        $builder = $this->createMock(Builder::class);

        $this->campaign->expects($this->once())->method('sent')->willReturn($builder);
        $builder->expects($this->once())->method('count')->willReturn($sentCampaigns);

        $this->assertEquals($sentCampaigns, $this->campaignRepository->getSentCount());
    }
    /**
     * @test
     * @covers ::getFailedCount
     */
    function it_should_return_failed_campaigns_count()
    {
        $failedCampaigns = $this->faker->randomDigitNotNull;
        $builder = $this->createMock(Builder::class);

        $this->campaign->expects($this->once())->method('failed')->willReturn($builder);
        $builder->expects($this->once())->method('count')->willReturn($failedCampaigns);

        $this->assertEquals($failedCampaigns, $this->campaignRepository->getFailedCount());
    }
}
