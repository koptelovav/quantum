<?php

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

Route::get('/cache', 'Api\CacheController@get');
Route::put('/cache', 'Api\CacheController@save');
Route::get('/cache/{id}', 'Api\CacheController@load');
Route::post('/cache/{id}', 'Api\CacheController@add');
Route::put('/cache/{id}', 'Api\CacheController@update');
Route::delete('/cache/{id}', 'Api\CacheController@delete');

Route::get('/db', 'Api\DbController@get');

Route::post('/reset', 'Api\AppController@reset');
