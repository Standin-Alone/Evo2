<?php

use Illuminate\Support\Facades\Route;

Route::get('/imc-uploading', 'IMCUploadingController@index')->name('imc-uploading');
Route::get('/get-ingest-imc-files', 'IMCUploadingController@get_ingest_imc_files')->name('get-ingest-imc-files');
Route::post('/ingest-imc-files', 'IMCUploadingController@ingest_imc_files')->name('ingest-imc-files');
Route::post('/upload-imc-files', 'IMCUploadingController@upload_imc_files')->name('upload-imc-files');