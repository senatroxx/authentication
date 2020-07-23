<?php

use Illuminate\Support\Facades\Route;

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
Route::prefix('google')->group(function (){
    Route::get('/', 'GoogleController@redirect');
    Route::get('callback', 'GoogleController@callback');
    Route::get('register', 'GoogleController@register')->name('google.register');
    Route::post('addUser', 'GoogleController@addUser')->name('google.addUser');
});