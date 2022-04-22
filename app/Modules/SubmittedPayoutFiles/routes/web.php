<?php
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\SubmittedPayoutFilesController;

Route::group([],function(){
    Route::resource('/SubmittedPayoutFiles','SubmittedPayoutFilesController');
    Route::get('/SubmittedPayoutFilesList','SubmittedPayoutFilesController@getSubmittedPayoutFilesList')->name('get.SubmittedPayoutFilesList');

    Route::post('/CompleteSubmittedPayoutFiles','SubmittedPayoutFilesController@completeSubmittedPayoutFiles')->name('complete.SubmittedPayoutFiles');
    Route::get('/CompletedPayoutTextfilesHistory','SubmittedPayoutFilesController@getCompletedPayoutTextfilesHistory')->name('get.CompletedPayoutTextfilesHistory');
    Route::get('/Total_Completed_Payout_Textfiles','SubmittedPayoutFilesController@getTotal_Completed_Payout_Textfiles')->name('get.Total_Completed_Payout_Textfiles');
});



