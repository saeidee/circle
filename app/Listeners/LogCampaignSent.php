<?php

namespace App\Listeners;

use App\Events\Campaign\CampaignSent;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Repositories\Campaign\CampaignRepositoryInterface;

/**
 * Class LogCampaignSent
 * @package App\Listeners
 */
class LogCampaignSent implements ShouldQueue
{
    /** @var CampaignRepositoryInterface */
    private $campaignRepository;

    /**
     * LogCampaignSent constructor.
     * @param CampaignRepositoryInterface $campaignRepository
     */
    public function __construct(CampaignRepositoryInterface $campaignRepository)
    {
        $this->campaignRepository = $campaignRepository;
    }

    /**
     * @param CampaignSent $campaignSent
     * @return void
     */
    public function handle(CampaignSent $campaignSent): void
    {
        $campaign = $this->campaignRepository->findByUuid($campaignSent->getUuid());

        $campaign->markAsSent($campaignSent->getProvider());
    }
}
