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

$app->group(['prefix' => 'api/v1'], function () use ($app) {
    /**
     * Cards Routes
     */
    $app->get('/cards', 'CardsController@index');
    $app->post('/cards', 'CardsController@store');
    $app->get('/cards/{id}', 'CardsController@show');
    $app->put('/cards/edit/{id}', 'CardsController@update');
    $app->patch('/cards/edit/{id}', 'CardsController@update');
    $app->delete('/cards/{id}', 'CardsController@destroy');
});