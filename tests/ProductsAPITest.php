<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class ProductsAPITest extends TestCase
{

    public function testUnauthorized() {
        $this->json('GET', '/api/product')
            ->assertResponseStatus(401)
            ->see('Unauthorized');
    }

    public function testMatchTotalNumber() {
        $api_token = $this->getTechnicianToken();

        $params = [
            'api_token' => $api_token,
        ];

        $response = json_decode($this->call("GET", "/api/product", $params)->content());

        $total = $this->getTotalProductsNumber();

        $this->assertEquals($total, $response->total);
    }

    private function getTechnicianToken() {
        $user = DB::table('users')->where('role', 'technician')->first();

        return $user->api_token;
    }

    private function getTotalProductsNumber() {
        $products_number = DB::table('products')->count();

        return $products_number;
    }

}
