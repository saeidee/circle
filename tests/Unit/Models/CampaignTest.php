<?php

namespace Tests\Unit\Models;

use Mockery;
use Tests\TestCase;
use App\Models\Campaign;
use Mockery\MockInterface;
use Illuminate\Database\Eloquent\Builder;
use PHPUnit\Framework\MockObject\MockObject;
use Illuminate\Foundation\Testing\WithFaker;

/**
 * Class CampaignTest
 * @package Tests\Unit\Models
 * @coversDefaultClass \App\Models\Campaign
 */
class CampaignTest extends TestCase
{
    use WithFaker;

    const FAILED = 0;
    const SENT = 2;
    const QUEUED = 1;

    /**
     * @test
     * @covers ::markAsSent
     */
    function it_should_mark_campaign_as_sent()
    {
        $provider = $this->faker->name;
        /** @var Campaign|MockInterface $campaign */
        $campaign = Mockery::mock(Campaign::class)->makePartial();

        $campaign->shouldReceive('save')->once();

        $campaign->markAsSent($provider);

        $this->assertEquals($provider, $campaign->getAttribute('provider'));
        $this->assertEquals(self::SENT, $campaign->getAttribute('status'));
    }

    /**
     * @test
     * @covers ::markAsFailed
     */
    function it_should_mark_campaign_as_failed()
    {
        $provider = $this->faker->name;
        /** @var Campaign|MockInterface $campaign */
        $campaign = Mockery::mock(Campaign::class)->makePartial();

        $campaign->shouldReceive('save')->once();

        $campaign->markAsFailed($provider);

        $this->assertEquals($provider, $campaign->getAttribute('provider'));
        $this->assertEquals(self::FAILED, $campaign->getAttribute('status'));
    }

    /**
     * @test
     * @covers ::scopeQueued
     */
    function it_should_filter_queued_campaign()
    {
        /** @var MockObject|Builder $builder */
        $builder = $this->createMock(Builder::class);
        $campaign = new Campaign();

        $builder->expects($this->once())->method('where')->with('status', self::QUEUED)->willReturnSelf();

        $this->assertInstanceOf(Builder::class, $campaign->scopeQueued($builder));
    }

    /**
     * @test
     * @covers ::scopeFailed
     */
    function it_should_filter_failed_campaign()
    {
        /** @var MockObject|Builder $builder */
        $builder = $this->createMock(Builder::class);
        $campaign = new Campaign();

        $builder->expects($this->once())->method('where')->with('status', self::FAILED)->willReturnSelf();

        $this->assertInstanceOf(Builder::class, $campaign->scopeFailed($builder));
    }

    /**
     * @test
     * @covers ::scopeSent
     */
    function it_should_filter_sent_campaign()
    {
        /** @var MockObject|Builder $builder */
        $builder = $this->createMock(Builder::class);
        $campaign = new Campaign();

        $builder->expects($this->once())->method('where')->with('status', self::SENT)->willReturnSelf();

        $this->assertInstanceOf(Builder::class, $campaign->scopeSent($builder));
    }
}
