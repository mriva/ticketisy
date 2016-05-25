<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Collections\TicketCollection;
use App\Ticket;
use App\TicketEvent;
use App\Exceptions\UnauthorizedAPIRequestException;

class TicketController extends RestController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $filters = $request->except('user');

        $requested_user = $request->input('user');
        $filters['user'] = $this->getFilterUser($requested_user);

        $services = TicketCollection::get($filters);
        return response()->json($services);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if ($this->user->cannot('create-ticket', $request)) {
            throw new UnauthorizedAPIRequestException;
        }

        $this->validate($request, [
            'service_id'    => 'required',
            'department_id' => 'required',
            'priority'      => 'required|in:normal,urgent,critical',
            'title'         => 'required',
            'description'   => 'required',
        ]);

        $data = $request->all();
        $data['user_id'] = $this->user->id;
        $data['status'] = 'pending';

        $ticket = Ticket::create($data);

        $ticket_event = TicketEvent::create([
            'ticket_id' => $ticket->id,
            'actor_id'  => $this->user->id,
            'action'    => 'create',
        ]);

        $ticket_event = TicketEvent::create([
            'ticket_id' => $ticket->id,
            'actor_id'  => $this->user->id,
            'action'    => 'comment',
            'value'     => $data['description'],
        ]);

        return [
            'status' => 'ok',
        ];
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $ticket = Ticket::find($id);

        if ($this->user->cannot('show', $ticket)) {
            throw new UnauthorizedAPIRequestException;
        }

        return $ticket;
    }

}
