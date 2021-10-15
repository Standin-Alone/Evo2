<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DisbursementExcelController;

Route::group([],function(){
    Route::resource('/DisbursementExcel','DisbursementExcelController');
    Route::get('/DisbursementListExcelList','DisbursementExcelController@getDisbursementListExcel')->name('get.DisbursementListExcel');
});



