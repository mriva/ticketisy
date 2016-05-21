<?php

namespace App\Listeners;

use Illuminate\Support\Facades\Log;
use App\Ticket;

class NotifyTicketAction {

    public function subscribe($events) {
        $events->listen(
            'App\Events\TicketActionCreate',
            'App\Listeners\NotifyTicketAction@action_create'
        );
        $events->listen(
            'App\Events\TicketActionComment',
            'App\Listeners\NotifyTicketAction@action_comment'
        );
        $events->listen(
            'App\Events\TicketActionPriority',
            'App\Listeners\NotifyTicketAction@action_priority'
        );
        $events->listen(
            'App\Events\TicketActionStatus',
            'App\Listeners\NotifyTicketAction@action_status'
        );
        $events->listen(
            'App\Events\TicketActionAssignee',
            'App\Listeners\NotifyTicketAction@action_assignee'
        );

    }

    public function action_create($event) {
        Log::info('ticket created');
    }

    public function action_comment($event) {
        Log::info('comment added');
    }

    public function action_priority($event) {
        Log::info('priority change');
    }

    public function action_status($event) {
        Log::info('status change');
    }

    public function action_assignee($event) {
        Log::info('assignee change');
    }

}
