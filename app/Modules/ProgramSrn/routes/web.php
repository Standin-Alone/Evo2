<?php
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\ProgramSrnController;

Route::group([],function(){

    Route::resource('/ProgramSrn','ProgramSrnController');    
    Route::get('/SupplierProgramList','ProgramSrnController@getSupplierProgramList')->name('get.SupplierProgramList');
    // Route::post('/addSupplierProgram', 'RegistrationController@addSupplierProgram');
    
});


