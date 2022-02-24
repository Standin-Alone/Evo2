<?php

use Illuminate\Support\Facades\Route;

Route::get('/returned-disbursement-uploading', 'ReturnedDisbursementController@index')->name('returned-disbursement-uploading');

Route::post('/returned-disbursement/upload-file', 'ReturnedDisbursementController@upload_file')->name('returned-disbursement-upload-file');
