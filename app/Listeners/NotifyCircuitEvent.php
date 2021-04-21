<?php

namespace App\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notifiable;
use App\Notifications\CircuitStateChanged;
use App\Events\CircuitBreaker\AbstractCircuitEvent;

/**
 * Class NotifyCircuitEvent
 * @package App\Listeners
 */
class NotifyCircuitEvent implements ShouldQueue
{
    use Notifiable;

    /**
     * @param AbstractCircuitEvent $circuitBreakerEvent
     * @return void
     */
    public function handle(AbstractCircuitEvent $circuitBreakerEvent): void
    {
        $this->notify(new CircuitStateChanged($circuitBreakerEvent));
    }

    /**
     * @return string|null
     */
    public function routeNotificationForSlack(): ?string
    {
        return config('services.slack.endpoint');
    }
}
