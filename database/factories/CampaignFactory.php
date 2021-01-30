<?php

use App\Enums\CampaignStatus;
use App\Models\Campaign;
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
