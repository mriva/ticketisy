<?php

namespace App\Collections;

use Illuminate\Support\Facades\DB;

class UserCollection extends RestCollection {
    
    protected $local_actions = [
        'role' => 'filterRole',
    ];

    public function __construct() {
        $this->resource = DB::table('users');

        $this->actions = array_merge($this->actions, $this->local_actions);
    }

    protected function filterRole($role) {
        $this->resource = $this->resource->where('role', $role);
    }

}

