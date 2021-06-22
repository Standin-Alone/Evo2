<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;

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


Route::get('/vmp', [LoginController::class, 'login'])->name('user.login');

// Supplier Registration Route
Route::group([],function(){
    Route::resource('/supplier','SupplierRegistrationController');
});









