<?php

namespace App\ValueObjects\Mail;

use App\Entities\Contact;

/**
 * Class Mail
 * @package App\ValueObjects
 */
final class Mail
{
    /*** @var string */
    private $subject;
    /*** @var Contact */
    private $to;
    /*** @var Contact */
    private $from;
    /*** @var Contact */
    private $replyTo;
    /*** @var MailContent */
    private $content;

    /**
     * Mail constructor.
     * @param string $subject
     * @param Contact $from
     * @param Contact[]|array $to
     * @param Contact $replyTo
     * @param MailContent $content
     */
    public function __construct(string $subject, Contact $from, array $to, Contact $replyTo, MailContent $content)
    {
        $this->to = $to;
        $this->from = $from;
        $this->subject = $subject;
        $this->content = $content;
        $this->replyTo = $replyTo;
    }

    /**
     * @return string
     */
    public function getSubject(): string
    {
        return $this->subject;
    }

    /**
     * @return Contact
     */
    public function getFrom(): Contact
    {
        return $this->from;
    }

    /**
     * @return Contact[]|array
     */
    public function getTo(): array
    {
        return $this->to;
    }

    /**
     * @return Contact
     */
    public function getReplyTo(): Contact
    {
        return $this->replyTo;
    }

    /**
     * @return MailContent
     */
    public function getContent(): MailContent
    {
        return $this->content;
    }
}
