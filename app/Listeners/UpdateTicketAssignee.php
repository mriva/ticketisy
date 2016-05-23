<?php

namespace App\Listeners;

use App\Events\TicketActionAssignee;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class UpdateTicketAssignee
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
     * @param  TicketActionAssignee  $event
     * @return void
     */
    public function handle(TicketActionAssignee $event)
    {
        $ticket = $event->ticket_event->ticket;

        $ticket->technician_id = $event->ticket_event->value['new'];
        $ticket->status = 'assigned';
        $ticket->save();
    }
}
