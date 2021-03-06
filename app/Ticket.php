<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{

    protected $fillable = [
        'user_id', 'service_id', 'department_id', 'priority', 'title', 'status'
    ];

    protected $with = ['department', 'user', 'technician', 'service', 'events'];

    public function events() {
        return $this->hasMany('App\TicketEvent');
    }

    public function user() {
        return $this->belongsTo('App\User');
    }

    public function technician() {
        return $this->belongsTo('App\User', 'technician_id');
    }

    public function service() {
        return $this->belongsTo('App\Service');
    }

    public function department() {
        return $this->belongsTo('App\Department');
    }

}
