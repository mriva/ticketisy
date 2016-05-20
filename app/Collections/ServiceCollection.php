<?php

namespace App\Collections;

use Illuminate\Support\Facades\DB;

class ServiceCollection extends RestCollection {
    
    protected $local_actions = [
        'user' => 'filterUser',
    ];

    public function __construct() {
        $this->resource = DB::table('services')
            ->join('products', 'services.product_id', '=', 'products.id');

        $this->actions = array_merge($this->actions, $this->local_actions);
    }

    protected function filterUser($user) {
        if ($user->role == 'admin') {
            return;
        }

        $this->resource = $this->resource->where('user_id', $user->id);
    }

}
