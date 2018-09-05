<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::group(['middleware' => ['auth:api']], function () {
    Route::resource('phonenumbers', 'API\PhonenumberController')->except([
        'create', 'edit'
    ]);

    Route::resource('clients', 'API\ClientController')->except([
        'create', 'edit'
    ]);

    Route::resource('users', 'API\UserController')->except([
        'create', 'edit'
    ]);
});
