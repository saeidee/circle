<?php

use App\Models\Campaign;
use App\Enums\CampaignStatus;
use Faker\Generator as Faker;
use Illuminate\Database\Eloquent\Factory;

/**
 * @var Factory $factory
 * Campaign Factory
 */
$factory->define(Campaign::class, function (Faker $faker) {
    return [
        'uuid' => $this->faker->uuid,
        'name' => $this->faker->name,
        'type' => $this->faker->randomElement(['text/plain', 'text/html']),
        'content' => $this->faker->text,
        'status' => $this->faker
            ->randomElement([CampaignStatus::FAILED, CampaignStatus::QUEUED, CampaignStatus::SENT]),
        'provider' => $this->faker->name,
    ];
});

$factory->state(Campaign::class, 'failed', ['status' => CampaignStatus::FAILED]);

$factory->state(Campaign::class, 'queued', ['status' => CampaignStatus::QUEUED]);

$factory->state(Campaign::class, 'sent', ['status' => CampaignStatus::SENT]);
