<?php

namespace App\Services\MailProviders;

use App\Entities\Contact;
use App\Enums\HttpMethodEnums;
use App\Enums\MailProviderEnums;
use App\ValueObjects\Mail\Mail;
use Illuminate\Support\Collection;

/**
 * Class SendGrid
 * @package App\Services\MailProviders
 */
class SendGrid implements MailSenderInterface
{
    /** @var Mail $mail */
    private $mail;

    /**
     * SendGrid constructor.
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
        return config('services.sendgrid.endpoints.send_email');
    }

    /**
     * @return Collection
     */
    public function getParameters(): Collection
    {
        $to = array_map(
            function (Contact $contact) {
                return $contact->toArray();
            },
            $this->mail->getTo()
        );

        return collect([
              'personalizations' => [
                ['to' => $to, 'subject' => $this->mail->getSubject()],
              ],
              'from' => $this->mail->getFrom()->toArray(),
              'reply_to' => $this->mail->getReplyTo()->toArray(),
              'content' => [$this->mail->getContent()->toArray()],
              'categories' => ['123'],
        ]);
    }

    /**
     * @return array
     */
    public function getHeaders(): array
    {
        $token = config('services.sendgrid.secret');

        return ['Authorization' => "Bearer {$token}", 'Content-Type' => 'application/json'];
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
        return MailProviderEnums::SEND_GRID;
    }
}
