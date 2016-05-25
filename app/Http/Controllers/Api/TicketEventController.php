<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\TicketEvent;
use App\Exceptions\UnauthorizedAPIRequestException;
use App\Ticket;

class TicketEventController extends RestController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'ticket_id' => 'required',
            'action'    => 'required|in:comment,priority,close,assignee',
        ]);

        $ticket = Ticket::find($request->input('ticket_id'));

        $this->authorize_action($request, $ticket);

        $data = $request->all();
        $data['actor_id'] = $this->user->id;
        if ($request->input('value') == 'me') {
            $data['value'] = $this->user->id;
        }

        TicketEvent::create($data);

        return [
            'status' => 'ok',
        ];
    }

    private function authorize_action($request, $ticket) {
        if ($this->user->cannot($request['action'], [$ticket, $request])) {
            throw new UnauthorizedAPIRequestException;
        }
    }

}
