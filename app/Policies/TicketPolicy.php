<?php

namespace App\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;
use App\Ticket;
use App\User;

class TicketPolicy
{
    use HandlesAuthorization;

    /**
     * Create a new policy instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    public function before(User $user, $ability) {
        if ($user->role == 'admin') {
            return true;
        }
    }

    public function show(User $user, Ticket $ticket) {
        if ($user->role == 'technician') {
            return true;
        }

        return $user->id == $ticket->user_id;
    }

    public function comment(User $user, Ticket $ticket) {
        if ($user->role == 'user') {
            return $user->id == $ticket->user_id;
        }

        return $user->id == $ticket->technician_id;
    }

    public function priority(User $user, Ticket $ticket)  {
        if ($user->role == 'user') {
            return false;
        }

        return $user->id == $ticket->technician_id;
    }

    public function close(User $user, Ticket $ticket) {
        if ($user->role == 'user') {
            return false;
        }

        return $user->id == $ticket->technician_id;
    }

    public function assignee(User $user, Ticket $ticket) {
        if ($user->role == 'user') {
            return false;
        }

        return $ticket->technician_id == 0 &&
            ($user->id == $ticket->technician_id ||
            $ticket->technician_id == 'me');
    }

}
