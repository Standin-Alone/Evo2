<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DisbursementReportsController;

Route::group([],function(){
    Route::resource('/DisbursementReports','DisbursementReportsController');
    Route::get('/DisbursementListReports_overall','DisbursementReportsController@getDisbursementListReports_overall')->name('get.DisbursementListReports_overall');
    Route::get('/DisbursementListReports_pending','DisbursementReportsController@getDisbursementListReports_pending')->name('get.DisbursementListReports_pending');
    Route::get('/DisbursementListReports_approved','DisbursementReportsController@getDisbursementListReports_approved')->name('get.DisbursementListReports_approved');
});




