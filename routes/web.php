<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', 'PageController@index');
Route::get('/pages', 'PageController@index');
Route::get('/page', 'PageController@show');
Route::get('/page/create', 'PageController@create')->middleware('auth');
Route::post('/page', 'PageController@store')->middleware('auth');
Auth::routes();
