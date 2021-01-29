<?php

namespace Tests\Unit\Resources;

use Tests\TestCase;
use App\Models\Campaign;
use App\Enums\CampaignStatus;
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

    const STATUSES = [
        CampaignStatus::FAILED => 'Failed',
        CampaignStatus::QUEUED => 'Queued',
        CampaignStatus::SENT => 'Sent',
    ];

    /**
     * @test
     * @covers ::toArray
     */
    function it_should_return_resource_as_array()
    {
        $campaign = new Campaign([
            'id' => $this->faker->randomDigitNotNull,
            'name' => $this->faker->name,
            'type' => $this->faker->randomElement(['text/plain', 'text/html']),
            'content' => $this->faker->text,
            'status' => $this->faker
                ->randomElement([CampaignStatus::FAILED, CampaignStatus::QUEUED, CampaignStatus::SENT]),
            'provider' => $this->faker->name,
        ]);
        $campaignResource = new CampaignResource($campaign);

        $this->assertEquals(
            [
                'id' => $campaign->id,
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
