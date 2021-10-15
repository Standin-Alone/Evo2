<?php
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\SupplierProfileController;

Route::group([],function(){
    Route::resource('/SupplierProfile','SupplierProfileController');
    Route::get('/SupplierList','SupplierProfileController@getSupplierList')->name('supplier.list');
});


