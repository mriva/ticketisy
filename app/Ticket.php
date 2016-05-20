<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{

    protected $fillable = [
        'user_id', 'service_id', 'department_id', 'priority', 'title'
    ];

}
