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

// Route::get('/dashboard', 'HomeController@index')->name('main.home');
Route::resource('/','AccessController');
Route::resource('/error','Controller');
Route::get('/sign-in','AccessController@signIn');
Route::post('/change-default-pass','AccessController@firstLoggedIn')->name('change-default-pass');
Route::get('/check-default-pass','AccessController@checkDefaultPass')->name('check-default-pass');
Route::get('/send_email','HomeController@send_email');

// Route::get('/login','AccessController@index');


