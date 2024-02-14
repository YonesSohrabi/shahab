<?php

use Illuminate\Support\Facades\Route;

$_namespace = 'Baham\Authentication\Controllers';


/*
 *
 *
        ================    API VERSION 1     ==============
 *
 *
*/

Route::group([
    'namespace' => $_namespace,
    'middleware' => ['api', 'guest'],
    'prefix' => 'api/v1'
], function ($router) {

    $router->post('/user/signup', 'UserAuthenticationController@signUp');
});


Route::group([
    'namespace' => $_namespace,
    'middleware' => ['api', 'auth:sanctum'],
    'prefix' => 'api/v1'
], function ($router) {

    $router->post('/user/logout', 'UserAuthenticationController@logout');
});


