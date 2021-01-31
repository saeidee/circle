<?php

namespace Tests\Feature\Controllers\API\V1;

use Tests\TestCase;
use App\Models\Campaign;
use App\Enums\CampaignStatus;
use Illuminate\Http\Response;
use App\Jobs\CampaignSenderManager;
use Illuminate\Support\Facades\Queue;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\DatabaseMigrations;

/**
 * Class CampaignControllerTest
 * @package Tests\Feature\Controllers\API\V1
 * @coversDefaultClass \App\Http\Controllers\API\V1\CampaignController
 */
class CampaignControllerTest extends TestCase
{
    use DatabaseMigrations, WithFaker;

    const TEXT = 'text/plain';
    const HTML = 'text/html';
    const SUCCESS = 'success';
    const STATUSES = [
        CampaignStatus::FAILED => 'failed',
        CampaignStatus::QUEUED => 'queued',
        CampaignStatus::SENT => 'sent',
    ];

    /**
     * @test
     * @covers ::index
     */
    function it_should_return_paginated_campaigns()
    {
        $campaigns = factory(Campaign::class, 10)
            ->create()
            ->transform(function (Campaign $campaign) {
                return [
                    'id' => $campaign->id,
                    'uuid' => $campaign->uuid,
                    'name' => $campaign->name,
                    'type' => $campaign->type,
                    'content' => $campaign->content,
                    'status' => self::STATUSES[$campaign->status],
                    'provider' => $campaign->provider,
                ];
            });

        $response = $this->get(route('campaigns', ['perPage' => 10]));

        $response->assertOk()->assertJsonFragment(['data' => $campaigns->toArray()]);
    }

    /**
     * @test
     * @covers ::store
     */
    function it_should_send_new_campaign()
    {
        Queue::fake();
        $parameters = [
            'campaign' => $this->faker->name,
            'subject' => $this->faker->text(10),
            'from' => ['name' => $this->faker->name, 'email' => $this->faker->email],
            'to' => [['name' => $this->faker->name, 'email' => $this->faker->email]],
            'replyTo' => ['name' => $this->faker->name, 'email' => $this->faker->email],
            'content' => [
                'type' => $this->faker->randomElement([self::TEXT, self::HTML]),
                'value' => $this->faker->randomHtml(),
            ],
        ];
        $response = $this->post(route('send-campaign'), $parameters);

        $response->assertOk()->assertExactJson([
            'type' => self::SUCCESS,
            'message' => 'Successfully Campaign created',
            'statusCode' => Response::HTTP_OK,
        ]);
        Queue::assertPushed(
            CampaignSenderManager::class,
            function (CampaignSenderManager $senderManager) use ($parameters) {
                $this->assertProperty($senderManager, 'providerName', config('app.initial_preferred_mail_provider'));

                return true;
            }
        );
    }
}
