<?php

use Illuminate\Support\Facades\Route;


Route::get('/kyc', 'KYCModuleController@index')->name('kyc.index');
Route::get('/kyc/show', 'KYCModuleController@show')->name('kyc.show');
Route::post('/kyc/import', 'KYCModuleController@import')->name('import-kyc');
