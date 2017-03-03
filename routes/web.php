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
     * Users Routes
     */
    $app->get('/users', 'UsersController@index');
    $app->post('/users', 'UsersController@store');
    $app->get('/users/{id}', 'UsersController@show');
    $app->get('/users/{id}/token', 'UsersController@generateToken');

    $app->group(['middleware' => 'auth'], function () use ($app) {
        /**
         * Cards Routes
         */
        $app->get('/cards', 'CardsController@index');
        $app->post('/cards', 'CardsController@store');
        $app->get('/cards/{id}', 'CardsController@show');
        $app->put('/cards/edit/{id}', 'CardsController@update');
        $app->patch('/cards/edit/{id}', 'CardsController@update');
        $app->delete('/cards/{id}', 'CardsController@destroy');
        $app->get('/cards/{id}/balance', 'CardsController@balance');
        $app->get('/cards/{id}/transactions', 'CardsController@transactions');
        $app->post('/cards/{id}/deposit', 'CardsController@deposit');

        /**
         * Merchants Routes
         */
        $app->post('/cards/{id}/authorization', 'CardsController@authorizationRequest');
        $app->post('/cards/{id}/capture/{authorization}', 'CardsController@capture');
        $app->post('/cards/{id}/refund/{authorization}', 'CardsController@refund');
    });
});