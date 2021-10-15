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


Route::get('/welcome', 'HomeController@index')->name('main.home');
// Route::resource('login','AccessController');
// Route::get('/login','AccessController@index');
Route::get('/error','Controller@error_page')->name('error_page.index');
Route::post('/change-default-pass','AccessController@firstLoggedIn')->name('change-default-pass');
Route::get('/check-default-pass','AccessController@checkDefaultPass')->name('check-default-pass');
