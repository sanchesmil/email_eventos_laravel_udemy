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

//Testa a classe de email App\Mail\NovoAcesso.php
Route::get('/mailable', function () {
    $user = App\User::find(1);
 
    return new App\Mail\NovoAcesso($user);
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
