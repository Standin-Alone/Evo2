<?php
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\PayoutSummaryController;

Route::group([],function(){
    
    Route::resource('/PayoutSummary','PayoutSummaryController');    
    Route::get('/getPayoutSummaryList','PayoutSummaryController@getPayoutSummaryList')->name('get.PayoutSummaryList');
    Route::get('/PayoutSummaryDetails','PayoutSummaryController@getPayoutSummaryDetails')->name('get.PayoutSummaryDetails');
    Route::get('/PayoutSummaryAttachments','PayoutSummaryController@getAttachmentsImg')->name('get.PayoutSummaryAttachments');

});


