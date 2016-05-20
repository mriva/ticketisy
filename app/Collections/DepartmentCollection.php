<?php

namespace App\Collections;

use Illuminate\Support\Facades\DB;

class DepartmentCollection extends RestCollection {
    
    protected $local_actions = [];

    public function __construct() {
        $this->resource = DB::table('departments');

        $this->actions = array_merge($this->actions, $this->local_actions);
    }

}

