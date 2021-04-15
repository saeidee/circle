<?php

namespace App\Jobs;

use Exception;
use App\Enums\CircuitEnums;
use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\Queue;
use App\Events\Campaign\CampaignSent;
use App\Factories\MailProviderFactory;
use App\Events\Campaign\CampaignFailed;
use App\Factories\CircuitManagerFactory;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\ValueObjects\Payloads\CampaignPayload;
use App\Services\MailProviders\MailProviderSwitcher;

/**
 * Class Deployer
 * @package App\Jobs
 */
class CampaignSender implements ShouldQueue
{
    use Queueable, InteractsWithQueue;

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
     * @param CircuitManagerFactory $circuitManagerFactory
     * @param MailProviderFactory $mailProviderFactory
     * @param MailProviderSwitcher $senderSwitcher
     * @throws Exception
     * @return void
     */
    public function handle(
        CircuitManagerFactory $circuitManagerFactory,
        MailProviderFactory $mailProviderFactory,
        MailProviderSwitcher $senderSwitcher
    ): void {
        $provider = $mailProviderFactory->make($this->providerName, $this->campaignPayload);
        $circuitManager = $circuitManagerFactory->make($provider);
        $circuitStatus = $circuitManager->makeRequest();

        if ($circuitStatus->isMaxAttemptReached()) {
            Queue::later(
                CircuitEnums::MAX_ATTEMPT_WAIT,
                new self($senderSwitcher->switch($this->providerName), $this->campaignPayload)
            );

            return;
        }

        if ($circuitStatus->isOpened()) {
            Queue::push(new self($senderSwitcher->switch($provider->getCircuitPrefix()), $this->campaignPayload));

            return;
        }

        event(new CampaignSent($this->providerName, $this->campaignPayload->getId()));
    }

    /**
     * @return void
     */
    public function failed(): void
    {
        event(new CampaignFailed($this->providerName, $this->campaignPayload->getId()));
    }
}
