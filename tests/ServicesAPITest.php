<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class ServicesAPITest extends TestCase
{
    use DatabaseTransactions;

    public function testUnauthorized() {
        $this->json('GET', '/api/service')
            ->assertResponseStatus(401)
            ->see('Unauthorized');
    }

    public function testReadListFilteredByUser() {
        $user = DB::table('users')->where('email', 'muradin@gmail.com')->first();

        $params = [
            'api_token' => $user->api_token,
        ];

        $response = json_decode($this->call("GET", "/api/service", $params)->content());

        $this->assertObjectHasAttribute('total', $response);
        $this->assertObjectHasAttribute('returned', $response);

        foreach ($response->data as $item) {
            $this->assertEquals($user->id, $item->user_id);
        }
    }

    public function testCreateDeniedForTechnician() {
        $user = DB::table('users')->where('email', 'tech_2@ticketisy.com')->first();

        $params = [
            'api_token' => $user->api_token,
            'product_id' => 3,
            'name' => 'Test name',
        ];

        $this->json('POST', '/api/service', $params)
            ->assertResponseStatus(401);
        
    }

    public function testCreateAllowedForUser() {
        $user = DB::table('users')->where('email', 'muradin@gmail.com')->first();

        $params = [
            'api_token' => $user->api_token,
            'product_id' => 3,
            'name' => 'Test user role',
        ];

        $this->json('POST', '/api/service', $params)
            ->assertResponseStatus(200)
            ->seeJson([
                'status' => 'ok',
            ]);
    }

}
