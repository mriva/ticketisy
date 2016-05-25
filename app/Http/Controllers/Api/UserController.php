<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Collections\UserCollection;
use App\User;
use App\Exceptions\UnauthorizedAPIRequestException;

class UserController extends RestController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $this->validate($request, [
            'role' => 'required',
        ]);

        $role = $request->input('role');

        if ($this->user->cannot('list-users', $role)) {
            throw new UnauthorizedAPIRequestException;
        }
        
        $filters = $request->all();

        $users = UserCollection::get($filters);
        return response()->json($users);
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
            'name'     => 'required|max:255',
            'email'    => 'required|email|max:255|unique:users',
            'password' => 'required|min:4|confirmed',
            'role'     => 'required|in:technician',
        ]);

        $data = $request->all();

        if ($this->user->cannot('create-user', $data['role'])) {
            throw new UnauthorizedAPIRequestException;
        }

        User::create([
            'name'      => $data['name'],
            'email'     => $data['email'],
            'password'  => bcrypt($data['password']),
            'role'      => $data['role'],
            'api_token' => str_random(60),
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
        $user = User::find($id);

        if ($this->user->cannot('show', $user)) {
            throw new UnauthorizedAPIRequestException;
        }

        return $user;
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
