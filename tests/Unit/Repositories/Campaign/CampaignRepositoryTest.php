<?php

namespace Tests\Unit\Repositories\Campaign;

use Tests\TestCase;
use App\Models\Campaign;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Pagination\LengthAwarePaginator;
use App\Repositories\Campaign\CampaignRepository;

/**
 * Class CampaignRepositoryTest
 * @package Tests\Unit\Repositories\Campaign
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

        $this->campaign = $this->getMockBuilder(Campaign::class)->addMethods(['paginate', 'create'])->getMock();
        $this->campaignRepository = new CampaignRepository($this->campaign);
    }

    /**
     * @test
     * @covers ::__construct
     * @covers ::paginate
     */
    function it_should_paginate_all_campaigns()
    {
        $lengthAwarePaginator = $this->createMock(LengthAwarePaginator::class);

        $this->campaign->expects($this->once())->method('paginate')->willReturn($lengthAwarePaginator);

        $this->assertEquals($lengthAwarePaginator, $this->campaignRepository->paginate());
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
}
