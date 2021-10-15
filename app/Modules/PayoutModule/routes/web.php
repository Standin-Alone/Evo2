<?php
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\PayoutModuleController;

Route::group([],function(){
    Route::resource('/PayoutModule','PayoutModuleController');
});


