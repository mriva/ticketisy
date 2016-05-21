<?php

namespace App\Collections;

use Illuminate\Support\Facades\DB;

class TicketCollection extends RestCollection {
    
    protected $local_actions = [
        'user' => 'filterUser',
        'service' => 'filterService',
        'status' => 'filterStatus',
    ];

    public function __construct() {
        $this->resource = DB::table('tickets')
            ->select('tickets.*', 'services.name AS service_name', 'products.title AS product_title')
            ->join('services', 'tickets.service_id', '=', 'services.id')
            ->join('products', 'services.product_id', '=', 'products.id');

        $this->actions = array_merge($this->actions, $this->local_actions);
    }

    protected function filterUser($user) {
        if ($user->role == 'admin') {
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

    public function filterService($service_id) {
        $this->resource = $this->resource->where('service_id', $service_id);
    }

    public function filterStatus($status) {
        $values = explode(',', $status);
        $this->resource = $this->resource->whereIn('status', $values);
    }

}
