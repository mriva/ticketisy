<?php

namespace App\Collections;

use Illuminate\Support\Facades\DB;

class TicketCollection extends RestCollection {
    
    protected $local_actions = [
        'user' => 'filterUser',
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

        $this->resource = $this->resource->where('tickets.user_id', $user->id);
    }

}
