<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Collections\TicketCollection;
use App\Ticket;
use App\TicketEvent;

class TicketController extends RestController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $filters = $request->all();
        $filters['user'] = $this->user;

        $services = TicketCollection::get($filters);
        return response()->json($services);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
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
            'service_id'    => 'required',
            'department_id' => 'required',
            'priority'      => 'required|in:normal,urgent,critical',
            'title'         => 'required',
            'description'   => 'required',
        ]);

        $data = $request->all();
        $data['user_id'] = $this->user->id;

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
            'value' => [
                'description' => $data['description'],
            ]
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

        return $ticket;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
