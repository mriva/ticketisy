<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{

    protected $fillable = [
        'user_id', 'service_id', 'department_id', 'priority', 'title'
    ];

    protected $with = ['user', 'service', 'department', 'events'];

    public function events() {
        return $this->hasMany('App\TicketEvent');
    }

    public function user() {
        return $this->belongsTo('App\User');
    }

    public function service() {
        return $this->belongsTo('App\Service');
    }

    public function department() {
        return $this->belongsTo('App\Department');
    }

}
