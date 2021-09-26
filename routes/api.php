<?php
/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

$router->get('/', function () {
    return 'ok';
});

$router->group(['prefix' => 'examples'], function () use ($router) {
    $router->get('/', 'ExampleController@index');
    $router->post('/', 'ExampleController@store');
    $router->get('/{id}', 'ExampleController@show');
    $router->put('/{id}', 'ExampleController@update');
    $router->delete('/{id}', 'ExampleController@destroy');
});
