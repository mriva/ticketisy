<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TicketEvent extends Model
{

    protected $table = 'tickets_events';

    protected $fillable = [
        'ticket_id', 'actor_id', 'action', 'value'
    ];

    protected $casts = [
        'value' => 'array',
    ];

    protected $with = ['actor'];

    public function actor() {
        return $this->belongsTo('App\User', 'actor_id');
    }

    public function ticket() {
        return $this->belongsTo('App\Ticket');
    }

    public static function create(array $attributes = []) {
        $model = new static($attributes);

        if (isset($attributes['value'])) {
            $value = $attributes['value'];
        } else {
            $value = null;
        }

        $model->process($value);

        return $model;
    }

    public function process($value) {
        $method = "action_{$this->action}";

        if (method_exists($this, $method)) {
            $this->$method($value);
        }
    }

    private function action_create($value) {
        echo "EVENT: create\n";
    }
    private function action_comment($value) {
        echo "EVENT: comment\n";

        $this->value = [
            'description' => $value,
        ];
    }
    private function action_status($value) {
        echo "EVENT: status change\n";

        $this->value = [
            'old' => $this->ticket->status,
            'new' => $value,
        ];
    }

}
