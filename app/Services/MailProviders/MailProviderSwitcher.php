<?php

namespace App\Services\MailProviders;

use Exception;
use Illuminate\Support\Arr;
use App\Enums\MailProviderEnums;

/**
 * Class MailProviderSwitcher
 * @package App\Services\MailProviders
 */
class MailProviderSwitcher
{
    const MAIL_SENDERS = [MailProviderEnums::SEND_GRID, MailProviderEnums::MAIL_JET];

    /**
     * @param string $current
     * @return string
     * @throws Exception
     */
    public function switch(string $current): string
    {
        return Arr::first(
            self::MAIL_SENDERS,
            function (string $element) use ($current) {
                return $element !== $current;
            }
        );
    }
}
