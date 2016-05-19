<?php

namespace App\Collections;

use Illuminate\Support\Facades\DB;

class ServiceCollection extends RestCollection {
    
    protected $local_actions = [
        'user' => 'filterUser',
    ];

    public function __construct() {
        $this->resource = DB::table('services');

        $this->actions = array_merge($this->actions, $this->local_actions);
    }

    protected function filterUser($user) {
        if ($user->ruolo->nome == 'master') {
            return;
        }

        $allowed_instances = DB::table('users_istanze')->where('user_id', $user->id)->lists('istanza_id');
        $this->resource = $this->resource->whereIn('istanze.id', $allowed_instances);
    }

}
