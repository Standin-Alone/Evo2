<?php
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\SubmitDisbursementController;

Route::group([],function(){
    Route::resource('/SubmitDisbursement','SubmitDisbursementController');
    Route::get('/SubmitDisbursementList', 'SubmitDisbursementController@getSubmitDisbursementList')->name('get.SubmitDisbursementList');
    Route::get('/generatedSubmitDisbursementlist', 'SubmitDisbursementController@getgeneratedSubmitDisbursementlist')->name('get.generatedSubmitDisbursementlist');
    Route::get('/SubmitDisbursementProvinceList', 'SubmitDisbursementController@getSubmitDisbursementProvinceList')->name('get.SubmitDisbursementProvinceList');

    Route::post('/GenerateDisbursement', 'SubmitDisbursementController@generateSubmitDisbursement')->name('generate.SubmitDisbursement');
    Route::post('/GenerateFinalDisbursement', 'SubmitDisbursementController@generateFinalSubmitDisbursement')->name('generate.FinalSubmitDisbursement');
    Route::get('/FinalSubmitDisbursementList', 'SubmitDisbursementController@getFinalSubmitDisbursementList')->name('get.FinalSubmitDisbursementList');
    Route::get('/generatedDisburseHistory', 'SubmitDisbursementController@getgeneratedDisburseHistory')->name('get.generatedDisburseHistory');
    
    Route::get('/SubmitDisbursementExcelFile', 'SubmitDisbursementController@downloadSubmitDisbursementExcelFile')->name('download.SubmitDisbursementExcelFile');
    Route::post('/SubmitDisbursementPin', 'SubmitDisbursementController@validateSubmitDisbursementPin')->name('validate.SubmitDisbursementPin');
});


