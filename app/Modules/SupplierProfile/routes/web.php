<?php
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\SupplierProfileController;

Route::group([],function(){
    Route::resource('/SupplierProfile','SupplierProfileController');
    Route::get('/SupplierProfileList','SupplierProfileController@getSupplierProfileList')->name('get.SupplierProfileList');

    Route::post('/SupplierProfileDetails','SupplierProfileController@getSupplierProfileDetails')->name('get.SupplierProfileDetails');

    Route::post('/SupplierGroupList','SupplierProfileController@getSupplierGroup')->name('get.SupplierGroup');
    Route::post('/SupplierBankList','SupplierProfileController@getSupplierBank')->name('get.SupplierBank');

    Route::post('/SupplierRegionList','SupplierProfileController@getSupplierRegion')->name('get.SupplierRegion');
    Route::post('/SupplierProvinceList','SupplierProfileController@getSupplierProvince')->name('get.SupplierProvince');
    Route::post('/SupplierMunicipalityList','SupplierProfileController@getSupplierMunicipality')->name('get.SupplierMunicipality');
    Route::post('/SupplierBarangayList','SupplierProfileController@getSupplierBarangay')->name('get.SupplierBarangay');

    Route::post('/SupplierSupplierProfileInsert', 'SupplierProfileController@Saving_SupplierProfile')->name('Saving.SupplierProfile');
    Route::post('/SupplierSupplierProfileUpdate', 'SupplierProfileController@Updating_SupplierProfile')->name('Updating.SupplierProfile');
    Route::post('/SupplierSupplierProfileRemove', 'SupplierProfileController@Removing_SupplierProfile')->name('Removing.SupplierProfile');
});


