<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class TicketsAPITest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testAccessFailsForUnauthorized()
    {
        $user = DB::table('users')->where('email', 'muradin@gmail.com')->first();
        $ticket = DB::table('tickets')->where('user_id', '!=', $user->id)->first();

        $params = [
            'api_token' => $user->api_token,
        ];

        $this->json("GET", "/api/ticket/{$ticket->id}", $params)
            ->assertResponseStatus(401)
            ->see('Unauthorized');
    }

    public function testCreateFailsForWrongService() {
        $user = DB::table('users')->where('email', 'muradin@gmail.com')->first();
        $service = DB::table('services')->where('user_id', '!=', $user->id)->first();

        $params = [
            'api_token'     => $user->api_token,
            'service_id'    => $service->id,
            'department_id' => '4',
            'priority'      => 'normal',
            'title'         => 'Titolo di test phpunit',
            'description'   => 'Descrizione interessantissima',
        ];

        $this->json('POST', '/api/ticket', $params)
            ->assertResponseStatus(401)
            ->see('Unauthorized');
    }
}
