<?php
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\SupplierProgramSetupController;

Route::group([],function(){
    Route::resource('/SupplierProgramSetup','SupplierProgramSetupController');  

    Route::get('/SupplierProgramList','SupplierProgramSetupController@getSupplierProgramList')->name('get.SupplierProgramList');

    Route::post('/SupplierProgramDetails','SupplierProgramSetupController@getSupplierProgramDetails')->name('get.SupplierProgramDetails');

    Route::post('/SupplierGroupList','SupplierProgramSetupController@getSupplierGroup')->name('get.SupplierGroup');
    Route::post('/SupplierBankList','SupplierProgramSetupController@getSupplierBank')->name('get.SupplierBank');

    Route::post('/SupplierRegionList','SupplierProgramSetupController@getSupplierRegion')->name('get.SupplierRegion');
    Route::post('/SupplierProvinceList','SupplierProgramSetupController@getSupplierProvince')->name('get.SupplierProvince');
    Route::post('/SupplierMunicipalityList','SupplierProgramSetupController@getSupplierMunicipality')->name('get.SupplierMunicipality');
    Route::post('/SupplierBarangayList','SupplierProgramSetupController@getSupplierBarangay')->name('get.SupplierBarangay');

    Route::post('/SupplierSupplierProgramInsert', 'SupplierProgramSetupController@Saving_SupplierProgram')->name('Saving.SupplierProgram');
    Route::post('/SupplierSupplierProgramUpdate', 'SupplierProgramSetupController@Updating_SupplierProgram')->name('Updating.SupplierProgram');
    Route::post('/SupplierSupplierProgramRemove', 'SupplierProgramSetupController@Removing_SupplierProgram')->name('Removing.SupplierProgram');
});