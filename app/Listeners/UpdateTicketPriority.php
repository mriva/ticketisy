<?php

namespace App\Listeners;

use App\Events\TicketActionPriority;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class UpdateTicketPriority
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  TicketActionPriority  $event
     * @return void
     */
    public function handle(TicketActionPriority $event)
    {
        $ticket = $event->ticket_event->ticket;

        $ticket->priority = $event->ticket_event->value['new'];
        $ticket->save();
    }
}
