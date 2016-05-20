<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TicketEvent extends Model
{

    protected $table = 'tickets_events';

    protected $fillable = [
        'ticket_id', 'actor_id', 'action', 'value'
    ];

    protected $casts = [
        'value' => 'array',
    ];

    protected $with = ['actor'];

    public function actor() {
        return $this->belongsTo('App\User', 'actor_id');
    }

}
