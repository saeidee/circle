<?php

namespace App\ValueObjects\Payloads;

use Ramsey\Uuid\Uuid;
use App\Entities\Contact;
use Illuminate\Support\Arr;
use App\ValueObjects\Mail\MailContent;

/**
 * Class CampaignPayload
 * @package App\ValueObjects\Requests
 */
final class CampaignPayload
{
    /** @var string */
    private $id;
    /** @var array */
    private $parameters;

    /**
     * CampaignPayload constructor.
     * @param array $parameters
     */
    public function __construct(array $parameters)
    {
        $this->parameters = $parameters;

        $this->generateId();
    }

    /**
     * @return Contact
     */
    public function getFrom(): Contact
    {
        return new Contact(
            Arr::get($this->parameters, 'from.name'),
            Arr::get($this->parameters, 'from.email')
        );
    }

    /**
     * @return Contact[]|array
     */
    public function getTo(): array
    {
        return array_map(
            function (array $recipient) {
                return new Contact($recipient['name'], $recipient['email']);
            },
            Arr::get($this->parameters, 'to')
        );
    }

    /**
     * @return Contact
     */
    public function getReplyTo(): Contact
    {
        return new Contact(
            Arr::get($this->parameters, 'replyTo.name'),
            Arr::get($this->parameters, 'replyTo.email')
        );
    }

    /**
     * @return MailContent
     */
    public function getContent(): MailContent
    {
        return new MailContent(
            Arr::get($this->parameters, 'content.type'),
            Arr::get($this->parameters, 'content.value')
        );
    }

    /**
     * @return string
     */
    public function getCampaign(): string
    {
        return Arr::get($this->parameters, 'campaign');
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return Arr::get($this->parameters, 'content.type');
    }

    /**
     * @return string
     */
    public function getSubject(): string
    {
        return Arr::get($this->parameters, 'subject');
    }

    /**
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * @return void
     */
    private function generateId(): void
    {
        $this->id = Uuid::uuid1();
    }

    /**
     * @return bool
     */
    public function isValid(): bool
    {
        if (!Arr::has($this->parameters, 'to')) {
            return false;
        }

        $areRecipientsValid = array_filter(
            Arr::get($this->parameters, 'to'),
            function ($recipient) {
                return Arr::accessible($recipient)
                    && Arr::get($recipient, 'name')
                    && Arr::get($recipient, 'email');
            }
        );

        return $areRecipientsValid
            && Arr::get($this->parameters, 'campaign')
            && Arr::get($this->parameters, 'subject')
            && Arr::get($this->parameters, 'from.email')
            && Arr::get($this->parameters, 'from.name')
            && Arr::get($this->parameters, 'replyTo.email')
            && Arr::get($this->parameters, 'replyTo.name')
            && Arr::get($this->parameters, 'content.type')
            && Arr::get($this->parameters, 'content.value');
    }
}
