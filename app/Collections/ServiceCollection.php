<?php

namespace App\Collections;

use Illuminate\Support\Facades\DB;

class ServiceCollection extends RestCollection {
    
    protected $local_actions = [
        'user' => 'filterUser',
    ];

    public function __construct() {
        $this->resource = DB::table('services')
            ->select('services.*', 'products.*', DB::raw('COUNT(tickets.id) AS open_tickets'))
            ->join('products', 'services.product_id', '=', 'products.id')
            ->leftJoin('tickets', 'tickets.service_id', '=', 'services.id')
            ->groupBy('services.id');

        $this->actions = array_merge($this->actions, $this->local_actions);
    }

    protected function filterUser($user) {
        if ($user->role == 'admin') {
            return;
        }

        $this->resource = $this->resource->where('services.user_id', $user->id);
    }

}
