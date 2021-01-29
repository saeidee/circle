<?php

namespace App\ValueObjects\Mail;

use App\Enums\EmailTypeEnums;
use Illuminate\Contracts\Support\Arrayable;

/**
 * Class MailContent
 * @package App\ValueObjects
 */
final class MailContent implements Arrayable
{
    /** @var string */
    private $type;
    /** @var string */
    private $value;

    /**
     * MailContent constructor.
     * @param string $type
     * @param string $value
     */
    public function __construct(string $type, string $value)
    {
        $this->type = $type;
        $this->value = $value;
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * @return string
     */
    public function getValue(): string
    {
        return $this->value;
    }

    /**
     * @return bool
     */
    public function isTextType(): bool
    {
        return $this->type === EmailTypeEnums::TEXT;
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        return ['type' => $this->getType(), 'value' => $this->getValue()];
    }
}
