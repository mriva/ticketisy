<?php

namespace App\Collections;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class TicketCollection extends RestCollection {
    
    protected $local_actions = [
        'user'       => 'filterUser',
        'service'    => 'filterService',
        'status'     => 'filterStatus',
        'technician' => 'filterTechnician',
    ];

    public function __construct() {
        $this->resource = DB::table('tickets')
            ->select([
                'tickets.*',
                'services.name AS service_name',
                'products.title AS product_title',
                'departments.name AS department',
            ])
            ->join('services', 'tickets.service_id', '=', 'services.id')
            ->join('products', 'services.product_id', '=', 'products.id')
            ->join('departments', 'tickets.department_id', '=', 'departments.id');

        $this->actions = array_merge($this->actions, $this->local_actions);
    }

    protected function filterUser($user) {
        if ($user == null) {
            return;
        }

        if ($user->role == 'technician') {
            $this->resource = $this->resource
                ->join('users_departments', 'tickets.department_id', '=', 'users_departments.department_id')
                ->where('users_departments.user_id', $user->id);

            return;
        }

        $this->resource = $this->resource->where('tickets.user_id', $user->id);
    }

    protected function filterService($service_id) {
        $this->resource = $this->resource->where('service_id', $service_id);
    }

    protected function filterStatus($status) {
        $values = explode(',', $status);
        $this->resource = $this->resource->whereIn('status', $values);
    }

    protected function filterTechnician($value) {
        if ($value == 'me') {
            $user = Auth::guard('api')->user();
            $this->resource = $this->resource->where('technician_id', $user->id);
        }
    }

}
