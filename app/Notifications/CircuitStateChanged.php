<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\SlackMessage;
use App\Events\CircuitBreaker\AbstractCircuitEvent;

/**
 * Class CircuitStateChanged
 * @package App\Notifications
 */
class CircuitStateChanged extends Notification implements ShouldQueue
{
    use Queueable;

    private $circuitBreakerEvent;

    /**
     * CircuitStateChanged constructor.
     * @param AbstractCircuitEvent $circuitBreakerEvent
     */
    public function __construct(AbstractCircuitEvent $circuitBreakerEvent)
    {
        $this->circuitBreakerEvent = $circuitBreakerEvent;
    }

    /**
     * @return array
     */
    public function via(): array
    {
        return ['slack'];
    }

    /**
     * @return SlackMessage
     */
    public function toSlack(): SlackMessage
    {
        return $this->getSlackMessageInstance()
            ->from('Circle', ':traffic_light:')
            ->to('circuit-breaker-channel')
            ->warning()
            ->content($this->circuitBreakerEvent->getMessage());
    }

    /**
     * @return SlackMessage
     */
    protected function getSlackMessageInstance(): SlackMessage
    {
        return new SlackMessage();
    }
}
