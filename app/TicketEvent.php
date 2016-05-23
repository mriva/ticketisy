<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Events\TicketActionCreate;
use App\Events\TicketActionComment;
use App\Events\TicketActionAssignee;
use App\Events\TicketActionClose;
use App\Events\TicketActionPriority;

class TicketEvent extends Model
{

    protected $table = 'tickets_events';

    protected $fillable = [
        'ticket_id', 'actor_id', 'action'
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

        $event = $model->process($value);
        $model->save();

        event(new $event($model));

        return $model;
    }

    public function process($value) {
        $method = "action_{$this->action}";

        if (method_exists($this, $method)) {
            $event = $this->$method($value);
        }

        return $event;
    }

    private function action_create($value) {
        return TicketActionCreate::class;
    }

    private function action_comment($value) {
        $this->value = [
            'description' => $value,
        ];

        return TicketActionComment::class;
    }

    private function action_priority($value) {
        $this->value = [
            'old' => $this->ticket->priority,
            'new' => $value,
        ];

        $this->ticket->priority = $value;
        $this->ticket->save();

        return TicketActionPriority::class;
    }

    private function action_close($value) {
        $this->value = [
            'message' => $value,
        ];

        $this->ticket->status = 'closed';
        $this->ticket->save();

        return TicketActionClose::class;
    }

    private function action_assignee($value) {
        $this->value = [
            'old' => $this->ticket->technician_id,
            'new' => $value,
        ];

        $this->ticket->technician_id = $value;
        $this->ticket->status = 'assigned';
        $this->ticket->save();

        return TicketActionAssignee::class;
    }

}
