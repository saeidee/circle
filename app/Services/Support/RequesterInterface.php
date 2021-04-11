<?php

namespace App\Services\Support;

use Psr\Http\Message\ResponseInterface;
use GuzzleHttp\Exception\GuzzleException;

/**
 * Interface RequesterInterface
 * @package App\Services\Support
 */
interface RequesterInterface
{
    /**
     * @throws GuzzleException
     * @return ResponseInterface
     */
    public function makeRequest(): ResponseInterface;
}
