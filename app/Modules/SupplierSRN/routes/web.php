<?php
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\SupplierSRNController;

Route::group([],function(){
    Route::resource('/SupplierSRN','SupplierSRNController');  
});