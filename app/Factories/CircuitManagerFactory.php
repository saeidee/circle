<?php

namespace App\Factories;

use GuzzleHttp\Client;
use App\Services\Support\Requester;
use App\Services\CircuitBreaker\CircuitManager;
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
    /** @var CircuitTrackerFactory */
    private $circuitTrackerFactory;

    /**
     * CircuitManagerFactory constructor.
     * @param Client $client
     * @param RequestFactory $requestFactory
     * @param CircuitTrackerFactory $circuitTrackerFactory
     */
    public function __construct(
        Client $client,
        RequestFactory $requestFactory,
        CircuitTrackerFactory $circuitTrackerFactory
    ) {
        $this->client = $client;
        $this->requestFactory = $requestFactory;
        $this->circuitTrackerFactory = $circuitTrackerFactory;
    }

    /**
     * @param MailSenderInterface $mailSender
     * @return CircuitManager
     */
    public function make(MailSenderInterface $mailSender): CircuitManager
    {
        $request = $this->requestFactory->make($mailSender);
        $requester = new Requester($this->client, $request);
        $circuitTracker = $this->circuitTrackerFactory->make($mailSender->getCircuitPrefix());

        return new CircuitManager($circuitTracker, $requester);
    }
}
