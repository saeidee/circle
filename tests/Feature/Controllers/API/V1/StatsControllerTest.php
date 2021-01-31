<?php

namespace Tests\Feature\Controllers\API\V1;

use Tests\TestCase;
use App\Models\Campaign;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\DatabaseMigrations;

/**
 * Class StatsControllerTest
 * @package Tests\Feature\Controllers\API\V1
 * @coversDefaultClass \App\Http\Controllers\API\V1\StatsController
 */
class StatsControllerTest extends TestCase
{
    use DatabaseMigrations, WithFaker;

    /**
     * @test
     * @covers ::index
     */
    function it_should_return_stats()
    {
        $sent = $this->faker->numberBetween(1, 10);
        $failed = $this->faker->numberBetween(1, 10);
        $queued = $this->faker->numberBetween(1, 10);

        factory(Campaign::class, $sent)->state('sent')->create();
        factory(Campaign::class, $failed)->state('failed')->create();
        factory(Campaign::class, $queued)->state('queued')->create();

        $response = $this->get(route('stats'));

        $response->assertOk()->assertExactJson(compact('sent', 'failed', 'queued'));
    }
}
