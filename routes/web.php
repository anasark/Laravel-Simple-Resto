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

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

Route::resource('menu', 'MenuController');
Route::post('menu/updateable/', 'MenuController@updateable');

Route::resource('meja', 'MejaController');
Route::post('meja/updateable/', 'MejaController@updateable');

Route::resource('pesanan', 'PesananController');
Route::get('pesan/{id}', 'PesananController@create');

Route::get('aktivitas', 'HomeController@activity');
Route::get('aktivitas/{id}', 'HomeController@activity');