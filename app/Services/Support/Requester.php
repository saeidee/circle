<?php

namespace App\Services\Support;

use GuzzleHttp\ClientInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use GuzzleHttp\Exception\GuzzleException;

/**
 * Class Requester
 * @package App\Services\Support
 */
class Requester implements RequesterInterface
{
    /** @var ClientInterface */
    private $client;
    /** @var RequestInterface */
    private $request;

    /**
     * Requester constructor.
     * @param ClientInterface $client
     * @param RequestInterface $request
     */
    public function __construct(ClientInterface $client, RequestInterface $request)
    {
        $this->client = $client;
        $this->request = $request;
    }

    /**
     * @throws GuzzleException
     * @return ResponseInterface
     */
    public function makeRequest(): ResponseInterface
    {
        return $this->client->send($this->request);
    }
}
