<?php

use Illuminate\Support\Facades\Route;

Route::get('/kyc', 'KYCModuleController@index');
Route::post('/kyc/import', 'KYCModuleController@import');
