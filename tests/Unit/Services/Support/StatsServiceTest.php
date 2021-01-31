<?php

namespace Tests\Unit\Services\Support;

use Tests\TestCase;
use App\Services\Support\StatsService;
use App\Factories\CircuitTrackerFactory;
use Illuminate\Foundation\Testing\WithFaker;
use App\Services\CircuitBreaker\CircuitTracker;
use App\Repositories\Campaign\CampaignRepositoryInterface;

/**
 * Class StatsServiceTest
 * @package Tests\Unit\Services\Support
 * @coversDefaultClass \App\Services\Support\StatsService
 */
class StatsServiceTest extends TestCase
{
    use WithFaker;

    const MAIL_JET = 'mailjet';
    const SEND_GRID = 'sendgrid';

    /**
     * @test
     * @covers ::__construct
     * @covers ::stats
     */
    function it_should_return_the_stats()
    {
        $mailJetCircuit = $this->faker->boolean;
        $sendGridCircuit = $this->faker->boolean;
        $sent = $this->faker->randomDigitNotNull;
        $failed = $this->faker->randomDigitNotNull;
        $queued = $this->faker->randomDigitNotNull;
        $campaignRepository = $this->createMock(CampaignRepositoryInterface::class);
        $circuitTrackerFactory = $this->createMock(CircuitTrackerFactory::class);
        $sendGridCircuitTracker = $this->createMock(CircuitTracker::class);
        $mailJetCircuitTracker = $this->createMock(CircuitTracker::class);
        $requestService = new StatsService($campaignRepository, $circuitTrackerFactory);

        $circuitTrackerFactory
            ->expects($this->exactly(2))
            ->method('make')
            ->withConsecutive([self::MAIL_JET], [self::SEND_GRID])
            ->willReturnOnConsecutiveCalls($mailJetCircuitTracker, $sendGridCircuitTracker);
        $mailJetCircuitTracker->expects($this->once())->method('isOpen')->willReturn($mailJetCircuit);
        $sendGridCircuitTracker->expects($this->once())->method('isOpen')->willReturn($sendGridCircuit);
        $campaignRepository->expects($this->once())->method('getSentCount')->willReturn($sent);
        $campaignRepository->expects($this->once())->method('getFailedCount')->willReturn($failed);
        $campaignRepository->expects($this->once())->method('getQueuedCount')->willReturn($queued);

        $this->assertEquals(
            compact('mailJetCircuit', 'sendGridCircuit', 'sent', 'failed', 'queued'),
            $requestService->stats()
        );
    }
}
