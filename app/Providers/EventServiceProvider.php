<?php

namespace App\Providers;

use App\Listeners\LogCampaignSent;
use App\Listeners\LogCampaignFailed;
use App\Events\Campaign\CampaignSent;
use App\Listeners\NotifyCircuitEvent;
use Illuminate\Auth\Events\Registered;
use App\Events\Campaign\CampaignFailed;
use App\Events\CircuitBreaker\CircuitClosed;
use App\Events\CircuitBreaker\CircuitOpened;
use App\Events\CircuitBreaker\CircuitMaxAttemptReached;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

/**
 * Class EventServiceProvider
 * @package App\Providers
 */
class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
        CircuitOpened::class => [
            NotifyCircuitEvent::class,
        ],
        CircuitClosed::class => [
            NotifyCircuitEvent::class,
        ],
        CircuitMaxAttemptReached::class => [
            NotifyCircuitEvent::class,
        ],
        CampaignSent::class => [
            LogCampaignSent::class,
        ],
        CampaignFailed::class => [
            LogCampaignFailed::class,
        ],
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();

        //
    }
}
