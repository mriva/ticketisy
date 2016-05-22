<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Collections\ServiceCollection;
use Illuminate\Support\Facades\Auth;
use App\Service;
use Illuminate\Support\Facades\Gate;

class ServiceController extends RestController
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

        $services = ServiceCollection::get($filters);
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
        if ($this->user->cannot('activate-service')) {
            return response([
                'auth' => 'User role not allowed'
            ], 401);
        }

        $this->validate($request, [
            'product_id' => 'required',
            'name'       => 'required',
        ]);

        $data = $request->all();
        $data['user_id'] = $this->user->id;

        Service::create($data);

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
        return response()->json(Service::find($id));
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
