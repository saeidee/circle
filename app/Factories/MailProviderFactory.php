<?php

namespace App\Factories;

use Exception;
use App\ValueObjects\Mail\Mail;
use App\Enums\MailProviderEnums;
use App\Services\MailProviders\MailJet;
use App\Services\MailProviders\SendGrid;
use App\ValueObjects\Payloads\CampaignPayload;
use App\Services\MailProviders\MailSenderInterface;

/**
 * Class MailProviderFactory
 * @package App\Factories
 */
class MailProviderFactory
{
    /**
     * @param string $provider
     * @param CampaignPayload $campaignPayload
     * @throws Exception
     * @return MailSenderInterface
     */
    public function make(string $provider, CampaignPayload $campaignPayload): MailSenderInterface
    {
        $mail = new Mail(
            $campaignPayload->getId(),
            $campaignPayload->getSubject(),
            $campaignPayload->getFrom(),
            $campaignPayload->getTo(),
            $campaignPayload->getReplyTo(),
            $campaignPayload->getContent()
        );

        switch ($provider) {
            case MailProviderEnums::SEND_GRID:
                return new SendGrid($mail);
            case MailProviderEnums::MAIL_JET:
                return new MailJet($mail);
            default:
                throw new Exception("Provider {$provider} is not configured");
        }
    }
}
