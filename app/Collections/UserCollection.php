<?php

namespace App\Collections;

use Illuminate\Support\Facades\DB;

class UserCollection extends RestCollection {
    
    protected $local_actions = [
        'role' => 'filterRole',
    ];

    public function __construct() {
        $this->resource = DB::table('users')->select('users.*');

        $this->actions = array_merge($this->actions, $this->local_actions);
    }

    protected function filterRole($role) {
        if ($role == 'user') {
            $this->resource = $this->resource
                ->addSelect(DB::raw('(SELECT COUNT(id) FROM services WHERE user_id = users.id) AS services_count'))
                ->addSelect(DB::raw("(SELECT COUNT(id) FROM tickets WHERE user_id = users.id AND status != 'closed') AS tickets_count"));
        }

        $this->resource = $this->resource->where('role', $role);
    }

}

