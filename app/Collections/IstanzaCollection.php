<?php

namespace App\Collections;

use App\Istanza;
use Illuminate\Support\Facades\DB;

class IstanzaCollection extends RestCollection {

    protected $local_actions = [
        'cliente' => 'filterCliente',
        'nome'    => 'filterNome',
    ];

    public function __construct() {
        $this->resource = DB::table('istanze')
            ->select('istanze.*', 'persone.ragione_sociale')
            ->join('persone', 'istanze.persona_id', '=', 'persone.id');

        $this->actions = array_merge($this->actions, $this->local_actions);
    }

    public function filterNome($value) {
        $this->resource = $this->resource->where('istanze.nome', 'LIKE', "%{$value}%");
    }

    public function filterCliente($value) {
        $this->resource = $this->resource->where('persone.ragione_sociale', 'LIKE', "%{$value}%");
    }

    public function filterUser($user) {
        if ($user->ruolo->nome == 'master') {
            return;
        }

        $allowed_instances = DB::table('users_istanze')->where('user_id', $user->id)->lists('istanza_id');
        $this->resource = $this->resource->whereIn('istanze.id', $allowed_instances);
    }

}
