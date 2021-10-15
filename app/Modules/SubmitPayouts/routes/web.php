<?php
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\SubmitPayoutsController;

Route::group([],function(){
    Route::resource('/SubmitPayouts','SubmitPayoutsController');
    Route::get('/SubmitPayoutsExport', 'SubmitPayoutsController@generateSupplierPayoutExport')->name('export.SupplierPayoutExcel');
    Route::get('/SubmitPayoutsList', 'SubmitPayoutsController@getSubmitPayoutsList')->name('get.SubmitPayoutsList');
    Route::get('/SubmitPayoutGeneratedList', 'SubmitPayoutsController@getSubmitPayoutGeneratedList')->name('get.SubmitPayoutGeneratedList');
    Route::post('/SupplierPayoutExcel', 'SubmitPayoutsController@generateSupplierPayoutExcel')->name('generate.SupplierPayoutExcel');
    Route::get('/SubmitteddHistoryList', 'SubmitPayoutsController@getSubmitteddHistoryList')->name('get.SubmitteddHistoryList');

    Route::get('/SubmitPayoutExcelFile', 'SubmitPayoutsController@downloadSubmitPayoutExcelFile')->name('download.SubmitPayoutExcelFile');
    Route::post('/SubmitPayoutPin', 'SubmitPayoutsController@validateSubmitPayoutPin')->name('validate.SubmitPayoutPin');

});


