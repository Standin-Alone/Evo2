<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HoldVoucherHistoryController;

Route::group([],function(){
    Route::resource('/HoldVoucherHistory','HoldVoucherHistoryController');
    Route::get('/HoldVoucherHistoryList','HoldVoucherHistoryController@getHoldVoucherHistoryList')->name('get.HoldVoucherHistoryList');
    Route::get('/HoldVoucherHistoryAttachmentsImg', 'HoldVoucherHistoryController@getHoldVoucherHistoryAttachmentsImg')->name('get.HoldVoucherHistoryAttachmentsImg');

    Route::post('/activateHoldVoucherTransaction', 'HoldVoucherHistoryController@activateHoldVoucherTransaction')->name('activate.HoldVoucherTransaction');
});
