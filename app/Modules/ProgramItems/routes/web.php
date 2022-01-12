<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\ProgramItemsController;

Route::group([],function(){
    Route::resource('/ProgramItems','ProgramItemsController');  
    Route::get('/ProgramItemsList','ProgramItemsController@getProgramItemsList')->name('get.ProgramItemsList');
    Route::post('/ProgramItemsDetails','ProgramItemsController@getProgramItemsDetails')->name('get.ProgramItemsDetails');

    Route::post('/ProgramRegionList','ProgramItemsController@getProgramRegion')->name('get.ProgramRegion');
    Route::post('/ProgramProvinceList','ProgramItemsController@getProgramProvince')->name('get.ProgramProvince');
});