<?php

namespace App\Factories;

use GuzzleHttp\Client;
use App\Services\Support\Requester;
use App\ValueObjects\CircuitBreaker\CircuitKeys;
use App\Services\CircuitBreaker\CircuitManager;
use App\Services\CircuitBreaker\CircuitTracker;
use App\Services\MailProviders\MailSenderInterface;

/**
 * Class CircuitManagerFactory
 * @package App\Factories
 */
class CircuitManagerFactory
{
    /** @var Client */
    private $client;
    /** @var RequestFactory */
    private $requestFactory;

    /**
     * CircuitManagerFactory constructor.
     * @param Client $client
     * @param RequestFactory $requestFactory
     */
    public function __construct(Client $client, RequestFactory $requestFactory)
    {
        $this->client = $client;
        $this->requestFactory = $requestFactory;
    }

    /**
     * @param MailSenderInterface $mailSender
     * @return CircuitManager
     */
    public function make(MailSenderInterface $mailSender): CircuitManager
    {
        $keys = new CircuitKeys($mailSender->getCircuitPrefix());
        $request = $this->requestFactory->make($mailSender);
        $requester = new Requester($this->client, $request);
        $circuitTracker = new CircuitTracker($keys);

        return new CircuitManager($circuitTracker, $requester);
    }
}
