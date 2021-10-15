<?php
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\SupplierModuleController;

Route::group([],function(){
    Route::resource('/SupplierModule','SupplierModuleController');
    Route::get('/DashboardTotal','SupplierModuleController@getDashboardTotal')->name('get.DashboardTotal');
    Route::get('/DashboardProgramList','SupplierModuleController@getDashboardProgramList')->name('get.DashboardProgramList');
    
    Route::post('/ProgramDefault','SupplierModuleController@updateProgramDefault')->name('update.ProgramDefault');
});


