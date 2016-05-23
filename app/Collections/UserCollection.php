<?php

namespace App\Collections;

use Illuminate\Support\Facades\DB;

class UserCollection extends RestCollection {
    
    protected $local_actions = [
        'role' => 'filterRole',
        'department' => 'filterDepartment',
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
        } elseif ($role == 'technician') {
            $this->resource = $this->resource
                ->addSelect(DB::raw("(SELECT COUNT(id) FROM tickets WHERE technician_id = users.id AND status != 'closed') AS tickets_count"))
                ->addSelect(DB::raw("(SELECT COUNT(id) FROM tickets WHERE technician_id = users.id) AS tickets_total_count"));
        }

        $this->resource = $this->resource->where('role', $role);
    }

    protected function filterDepartment($department_id) {
        $this->resource = $this->resource
            ->join('users_departments', 'users.id', '=', 'users_departments.user_id')
            ->where('users_departments.department_id', $department_id);
    }

}

