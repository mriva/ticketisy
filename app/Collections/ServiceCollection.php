<?php

namespace App\Collections;

use Illuminate\Support\Facades\DB;

class ServiceCollection extends RestCollection {
    
    protected $local_actions = [
        'user' => 'filterUser',
    ];

    public function __construct() {
        $this->resource = DB::table('services')
            ->select([
                'services.*',
                'products.title',
                'products.description',
                DB::raw("(SELECT COUNT(id) FROM tickets WHERE service_id = services.id AND status != 'closed') AS open_tickets"),
                DB::raw("(SELECT COUNT(id) FROM tickets WHERE service_id = services.id) AS total_tickets")
            ])
            ->join('products', 'services.product_id', '=', 'products.id')
            ->groupBy('services.id');

        $this->actions = array_merge($this->actions, $this->local_actions);
    }

    protected function filterUser($user) {
        if ($user == null) {
            return;
        }

        $this->resource = $this->resource->where('services.user_id', $user->id);
    }

}
