<?php

namespace App\Services\MailProviders;

use App\Entities\Contact;
use App\Enums\HttpMethodEnums;
use App\Enums\MailProviderEnums;
use App\ValueObjects\Mail\Mail;
use Illuminate\Support\Collection;

/**
 * Class MailJet
 * @package App\Services\MailProviders
 */
class MailJet implements MailSenderInterface
{
    /** @var Mail */
    private $mail;

    /**
     * MailJet constructor.
     * @param Mail $mail
     */
    public function __construct(Mail $mail)
    {
        $this->mail = $mail;
    }

    /**
     * @return string
     */
    public function getUrl(): string
    {
        return config('services.mailjet.endpoints.send_email');
    }

    /**
     * @return Collection
     */
    public function getParameters(): Collection
    {
        $htmlPart = '';
        $textPart = '';
        $content = $this->mail->getContent();
        $to = array_map(
            function (Contact $contact) {
                return ['Email' => $contact->getEmail(), 'Name' => $contact->getName()];
            },
            $this->mail->getTo()
        );

        if ($content->isTextType()) {
            $textPart = $content->getValue();
        } else {
            $htmlPart = $content->getValue();
        }

        return collect([
          'Messages' => [
            [
              'From' => [
                'Email' => $this->mail->getFrom()->getEmail(),
                'Name' => $this->mail->getFrom()->getName()
              ],
              'To' => $to,
              'Subject' => $this->mail->getSubject(),
              'TextPart' => $textPart,
              'HTMLPart' => $htmlPart,
              'CustomID' => $this->mail->getId(),
            ],
          ],
        ]);
    }

    /**
     * @return array
     */
    public function getHeaders(): array
    {
        $token = config('services.mailjet.secret');

        return ['Authorization' => "Basic {$token}", 'Content-Type' => 'application/json'];
    }

    /**
     * @return string
     */
    public function getHttpMethod(): string
    {
        return HttpMethodEnums::POST;
    }

    /**
     * @return string
     */
    public function getCircuitPrefix(): string
    {
        return MailProviderEnums::MAIL_JET;
    }
}
