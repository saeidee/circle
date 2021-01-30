<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Jobs\CampaignSenderManager;
use Illuminate\Support\Facades\Queue;
use App\ValueObjects\Payloads\CampaignPayload;

/**
 * Class SendCampaign
 * @package App\Console\Commands
 */
class SendCampaign extends Command
{
    /** @var string */
    protected $signature = 'circle:send-campaign
                                {payload : The json payload, please check the readme for its structure}';
    /** @var string */
    protected $description = 'Sending a campaign to their related recipients';

    /**
     * @return void
     */
    public function handle(): void
    {
        $payload = $this->preparePayload();

        if (!$payload->isValid()) {
            $this->warn(
                'The json structure which you passed is different from our defined structure, please check readme.'
            );

            return;
        }

        Queue::push(new CampaignSenderManager(config('app.initial_preferred_mail_provider'), $payload));

        $this->info('Great, we are preparing to send the campaign.');
    }

    /**
     * @return CampaignPayload
     */
    protected function preparePayload(): CampaignPayload
    {
        $parameters = json_decode(json_encode(json_decode($this->argument('payload'))), true);

        return new CampaignPayload($parameters);
    }
}
