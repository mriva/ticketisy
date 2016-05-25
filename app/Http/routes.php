<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

Route::auth();

Route::get('/', 'HomeController@index');

Route::get('/unauthorized', function() {
    return response('Unauthorized request');
});

Route::resource('/api/service', 'Api\ServiceController', ['only' => ['index', 'store', 'show']]);
Route::resource('/api/product', 'Api\ProductController', ['only' => ['index']]);
Route::resource('/api/ticket', 'Api\TicketController', ['only' => ['index', 'store', 'show']]);
Route::resource('/api/ticketevent', 'Api\TicketEventController', ['only' => ['store']]);
Route::resource('/api/department', 'Api\DepartmentController', ['only' => ['index']]);
Route::resource('/api/user', 'Api\UserController', ['only' => ['index', 'store', 'show']]);
