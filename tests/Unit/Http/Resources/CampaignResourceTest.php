<?php

namespace Tests\Unit\Http\Resources;

use Tests\TestCase;
use App\Models\Campaign;
use App\Http\Resources\CampaignResource;
use Illuminate\Foundation\Testing\WithFaker;

/**
 * Class CampaignResourceTest
 * @package Tests\Unit\Resources
 * @coversDefaultClass \App\Http\Resources\CampaignResource
 */
class CampaignResourceTest extends TestCase
{
    use WithFaker;

    const FAILED = 0;
    const QUEUED = 1;
    const SENT = 2;
    const STATUSES = [
        self::FAILED => 'failed',
        self::QUEUED => 'queued',
        self::SENT => 'sent',
    ];

    /**
     * @test
     * @covers ::toArray
     */
    function it_should_return_resource_as_array()
    {
        /** @var Campaign $campaign */
        $campaign = factory(Campaign::class)->make(['id' => $this->faker->randomDigitNotNull]);
        $campaignResource = new CampaignResource($campaign);

        $this->assertEquals(
            [
                'id' => $campaign->id,
                'uuid' => $campaign->uuid,
                'name' => $campaign->name,
                'type' => $campaign->type,
                'content' => $campaign->content,
                'status' => self::STATUSES[$campaign->status],
                'provider' => $campaign->provider,
            ],
            $campaignResource->resolve()
        );
    }
}
