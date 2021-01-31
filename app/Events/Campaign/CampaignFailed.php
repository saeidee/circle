<?php

namespace App\Events\Campaign;

use Illuminate\Contracts\Queue\ShouldQueue;

/**
 * Class CampaignFailed
 * @package App\Events\Campaign
 */
class CampaignFailed implements ShouldQueue
{
    /** @var string */
    private $provider;
    /** @var string */
    private $uuid;

    /**
     * CampaignSent constructor.
     * @param string $provider
     * @param string $uuid
     */
    public function __construct(string $provider, string $uuid)
    {
        $this->provider = $provider;
        $this->uuid = $uuid;
    }

    /**
     * @return string
     */
    public function getProvider(): string
    {
        return $this->provider;
    }

    /**
     * @return string
     */
    public function getUuid(): string
    {
        return $this->uuid;
    }
}
