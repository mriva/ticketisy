<?php

namespace App\Collections;

use Illuminate\Support\Facades\DB;

class DepartmentCollection extends RestCollection {
    
    protected $local_actions = [
        'technician' => 'filterTechnician',
    ];

    public function __construct() {
        $this->resource = DB::table('departments');

        $this->actions = array_merge($this->actions, $this->local_actions);
    }

    protected function filterTechnician($technician_id) {
        $this->resource = $this->resource
            ->join('users_departments', 'departments.id', '=', 'users_departments.department_id')
            ->where('users_departments.user_id', $technician_id);
    }

}

