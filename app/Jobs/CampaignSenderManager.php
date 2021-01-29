<?php

namespace App\Jobs;

use App\Events\CampaignFailed;
use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\Queue;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\ValueObjects\Payloads\CampaignPayload;
use App\Repositories\Campaign\CampaignRepositoryInterface;

/**
 * Class CampaignSenderManager
 * @package App\Jobs
 */
class CampaignSenderManager implements ShouldQueue
{
    use Queueable;

    /** @var string */
    private $providerName;
    /** @var CampaignPayload */
    private $campaignPayload;

    /**
     * CampaignSender constructor.
     * @param string $providerName
     * @param CampaignPayload $campaignPayload
     */
    public function __construct(string $providerName, CampaignPayload $campaignPayload)
    {
        $this->providerName = $providerName;
        $this->campaignPayload = $campaignPayload;
    }

    /**
     * @param CampaignRepositoryInterface $campaignRepository
     * @return void
     */
    public function handle(CampaignRepositoryInterface $campaignRepository): void
    {
        $campaignRepository->create([
            'uuid' => $this->campaignPayload->getId(),
            'name' => $this->campaignPayload->getCampaign(),
            'type' => $this->campaignPayload->getContent()->getType(),
            'content' => $this->campaignPayload->getContent()->getValue(),
        ]);

        Queue::push(new CampaignSender($this->providerName, $this->campaignPayload));
    }

    /**
     * @return void
     */
    public function failed(): void
    {
        event(new CampaignFailed($this->providerName, $this->campaignPayload->getId()));
    }
}
