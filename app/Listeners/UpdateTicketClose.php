<?php

namespace App\Listeners;

use App\Events\TicketActionClose;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class UpdateTicketClose
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
     * @param  TicketActionClose  $event
     * @return void
     */
    public function handle(TicketActionClose $event)
    {
        $ticket = $event->ticket_event->ticket;

        $ticket->status = 'closed';
        $ticket->save();
    }
}
