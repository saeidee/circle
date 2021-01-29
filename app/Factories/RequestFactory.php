<?php

namespace App\Factories;

use GuzzleHttp\Psr7\Request;
use App\Services\MailProviders\MailSenderInterface;

/**
 * Class RequestFactory
 * @package App\Factories
 */
class RequestFactory
{
    /**
     * @param MailSenderInterface $mailSender
     * @return Request
     */
    public function make(MailSenderInterface $mailSender): Request
    {
        return new Request(
            $mailSender->getHttpMethod(),
            $mailSender->getUrl(),
            $mailSender->getHeaders(),
            $mailSender->getParameters()->toJson()
        );
    }
}
