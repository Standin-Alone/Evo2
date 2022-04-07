<?php

use Illuminate\Support\Facades\Route;

Route::get('/returned-disbursement-uploading', 'ReturnedDisbursementController@index')->name('returned-disbursement-uploading')->middleware('ensure.user.has.role');

Route::post('/returned-disbursement/upload-file', 'ReturnedDisbursementController@upload_file')->name('returned-disbursement-upload-file');
Route::get('/returned-disbursement-uploading/get-files', 'ReturnedDisbursementController@get_files')->name('list-of-ingested-files-datatable');
Route::get('/returned-disbursement-uploading/show-more/${return_file_id}', 'ReturnedDisbursementController@show_more')->name('dbp-returned-files-show-more');
Route::get('/returned-disbursement-uploading/show-error-logs/${filename}', 'ReturnedDisbursementController@show_error_logs')->name('dbp-return-error-logs');