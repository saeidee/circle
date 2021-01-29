<?php

namespace App\Listeners;

use App\Events\CampaignFailed;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Repositories\Campaign\CampaignRepositoryInterface;

/**
 * Class LogCampaignFailed
 * @package App\Listeners
 */
class LogCampaignFailed implements ShouldQueue
{
    /** @var CampaignRepositoryInterface */
    private $campaignRepository;

    /**
     * LogCampaignFailed constructor.
     * @param CampaignRepositoryInterface $campaignRepository
     */
    public function __construct(CampaignRepositoryInterface $campaignRepository)
    {
        $this->campaignRepository = $campaignRepository;
    }

    /**
     * @param CampaignFailed $campaignFailed
     * @return void
     */
    public function handle(CampaignFailed $campaignFailed): void
    {
        $campaign = $this->campaignRepository->findByUuid($campaignFailed->getUuid());

        $campaign->markAsFailed($campaignFailed->getProvider());
    }
}
