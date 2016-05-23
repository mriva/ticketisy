<?php

namespace App\Providers;

use Illuminate\Contracts\Events\Dispatcher as DispatcherContract;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        'App\Events\TicketActionCreate' => [
        ],
        'App\Events\TicketActionComment' => [
        ],
        'App\Events\TicketActionPriority' => [
            'App\Listeners\UpdateTicketPriority',
        ],
        'App\Events\TicketActionClose' => [
            'App\Listeners\UpdateTicketClose',
        ],
        'App\Events\TicketActionAssignee' => [
            'App\Listeners\UpdateTicketAssignee',
        ],
    ];

    protected $subscribe = [
        'App\Listeners\NotifyTicketAction',
    ];

    /**
     * Register any other events for your application.
     *
     * @param  \Illuminate\Contracts\Events\Dispatcher  $events
     * @return void
     */
    public function boot(DispatcherContract $events)
    {
        parent::boot($events);

        //
    }
}
