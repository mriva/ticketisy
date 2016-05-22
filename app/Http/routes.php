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

Route::resource('/api/service', 'Api\ServiceController');
Route::resource('/api/product', 'Api\ProductController');
Route::resource('/api/ticket', 'Api\TicketController');
Route::resource('/api/ticketevent', 'Api\TicketEventController');
Route::resource('/api/department', 'Api\DepartmentController');
Route::resource('/api/user', 'Api\UserController');
