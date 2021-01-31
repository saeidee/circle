<?php

namespace App\Services\Support;

use App\Enums\MailProviderEnums;
use App\Factories\CircuitTrackerFactory;
use App\Repositories\Campaign\CampaignRepositoryInterface;

/**
 * Class StatsService
 * @package App\Services\Support
 */
class StatsService
{
    /** @var CampaignRepositoryInterface */
    private $campaignRepository;
    /** @var CircuitTrackerFactory */
    private $circuitTrackerFactory;

    /**
     * StatsService constructor.
     * @param CampaignRepositoryInterface $campaignRepository
     * @param CircuitTrackerFactory $circuitTrackerFactory
     */
    public function __construct(
        CampaignRepositoryInterface $campaignRepository,
        CircuitTrackerFactory $circuitTrackerFactory
    ) {
        $this->campaignRepository = $campaignRepository;
        $this->circuitTrackerFactory = $circuitTrackerFactory;
    }

    /**
     * @return array
     */
    public function stats(): array
    {
        $mailJetCircuitTracker = $this->circuitTrackerFactory->make(MailProviderEnums::MAIL_JET);
        $sendGridCircuitTracker = $this->circuitTrackerFactory->make(MailProviderEnums::SEND_GRID);

        return [
            'mailJetCircuit' => $mailJetCircuitTracker->isOpen(),
            'sendGridCircuit' => $sendGridCircuitTracker->isOpen(),
            'sent' => $this->campaignRepository->getSentCount(),
            'failed' => $this->campaignRepository->getFailedCount(),
            'queued' => $this->campaignRepository->getQueuedCount(),
        ];
    }
}
