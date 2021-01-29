<?php

namespace App\Services\MailProviders;

use Illuminate\Support\Collection;

/**
 * Interface AbstractRequestCollection
 * @package App\Services\MailProviders
 */
interface MailSenderInterface
{
    /**
     * @return string
     */
    public function getUrl(): string;

    /**
     * @return Collection
     */
    public function getParameters(): Collection;

    /**
     * @return array
     */
    public function getHeaders(): array;

    /**
     * @return string
     */
    public function getHttpMethod(): string;

    /**
     * @return string
     */
    public function getCircuitPrefix(): string;
}
