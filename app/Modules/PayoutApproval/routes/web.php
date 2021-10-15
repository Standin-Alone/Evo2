<?php
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\PayoutApprovalController;

Route::group([],function(){
    Route::resource('/PayoutApproval','PayoutApprovalController');
    Route::get('/PayoutApprovalList', 'PayoutApprovalController@getPayoutApprovalList')->name('get.PayoutApprovalList');
    Route::get('/PayoutApprovalDetails', 'PayoutApprovalController@getPayoutApprovalDetails')->name('get.PayoutApprovalDetails');
    Route::get('/ApprovalHistoryDetails', 'PayoutApprovalController@getApprovalHistoryDetails')->name('get.ApprovalHistoryDetails');
    Route::get('/getHoldTransactionDetails', 'PayoutApprovalController@getHoldTransactionDetails')->name('get.HoldTransactionDetails');
    Route::get('/VoucherAttachmentsImg', 'PayoutApprovalController@getVoucherAttachmentsImg')->name('get.VoucherAttachmentsImg');
    Route::get('/ApprovedHistoryList', 'PayoutApprovalController@getApprovedHistoryList')->name('get.ApprovedHistoryList');

    Route::post('/holdSelectedVoucher', 'PayoutApprovalController@holdSelectedVoucher')->name('hold.SelectedVoucher');
    Route::post('/approve1SelectedBatch', 'PayoutApprovalController@approve1SelectedBatch')->name('approve1.SelectedBatch');
    Route::post('/approve2SelectedBatch', 'PayoutApprovalController@approve2SelectedBatch')->name('approve2.SelectedBatch');

});


