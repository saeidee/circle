<?php

namespace App\Jobs;

use Exception;
use App\Events\CampaignSent;
use Illuminate\Bus\Queueable;
use Illuminate\Support\Carbon;
use App\Events\CampaignFailed;
use Illuminate\Support\Facades\Queue;
use App\Factories\MailProviderFactory;
use App\Factories\CircuitManagerFactory;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Events\CircuitBreaker\CircuitOpened;
use App\Events\CircuitBreaker\CircuitClosed;
use App\ValueObjects\Payloads\CampaignPayload;
use App\Services\MailProviders\MailProviderSwitcher;
use App\Events\CircuitBreaker\CircuitMaxAttemptReached;

/**
 * Class Deployer
 * @package App\Jobs
 */
class CampaignSender implements ShouldQueue
{
    use Queueable, InteractsWithQueue;

    const MAX_ATTEMPT_WAIT = Carbon::SECONDS_PER_MINUTE * 5;

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
     * @return void
     * @throws Exception
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
                self::MAX_ATTEMPT_WAIT,
                new self($senderSwitcher->switch($this->providerName), $this->campaignPayload)
            );

            event(new CircuitMaxAttemptReached($this->providerName));

            return;
        }

        if ($circuitStatus->isOpened()) {
            Queue::push(new self($senderSwitcher->switch($provider->getCircuitPrefix()), $this->campaignPayload));

            event(new CircuitOpened($this->providerName));

            return;
        }

        if ($circuitStatus->wasHalfOpen()) {
            event(new CircuitClosed($this->providerName));

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
