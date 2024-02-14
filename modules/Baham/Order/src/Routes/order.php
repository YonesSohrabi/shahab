<?php

use \Illuminate\Support\Facades\Route;

Route::group([
    "namespace" => "Baham\Order\Controllers",
    'middleware' => ['auth:sanctum'],
    'prefix' => 'api/v1'
], function ($router) {

    /*
     * user account route
     *
     */

    $router->get('/orders','OrderController@ordersForUserToFollow');
    $router->post('/orders','OrderController@placeOrder');
    $router->put('/orders/{order}/update','OrderController@followOrder');

});

