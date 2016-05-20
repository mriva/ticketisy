<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TicketEvent extends Model
{

    protected $table = 'tickets_events';

    protected $fillable = [
        'ticket_id', 'action', 'value'
    ];

    protected $casts = [
        'value' => 'array',
    ];

}
