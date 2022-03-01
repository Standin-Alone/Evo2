<?php
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\SupplierPayoutController;

Route::group([],function(){
    Route::resource('/SupplierPayout','SupplierPayoutController');
    Route::get('/SupplierClaimedVoucher', 'SupplierPayoutController@getSupplierClaimedVoucher')->name('get.SupplierPayout_ClaimedVoucherDetails');
    Route::get('/SupplierPayoutList', 'SupplierPayoutController@getSupplierPayout')->name('get.SupplierPayoutList');
    Route::get('/SupplierPayoutContent', 'SupplierPayoutController@getSupplierPayoutContent')->name('get.SupplierPayoutContent');
    Route::get('/BatchPayoutDropList', 'SupplierPayoutController@getBatchPayoutDropList')->name('get.BatchPayoutDropList');
    Route::get('/batchpayoutdetails', 'SupplierPayoutController@getBatchpayoutdetails')->name('get.SupplierBatchPayoutDetails');
    Route::get('/HoldTransactionDetails', 'SupplierPayoutController@getHoldTransactionDetails')->name('get.SupplierPayout_HoldTransDetails');
    Route::get('/StatusBatchPayout', 'SupplierPayoutController@getStatusBatchPayout')->name('get.SupplierPayout_Status');
    Route::get('/HoldTransAttachmentsImg', 'SupplierPayoutController@getHoldTransAttachmentsImg')->name('get.SupplierPayoutAttachmentsImg');
    Route::get('/SupplierPayout_ViewTotalDetails', 'SupplierPayoutController@getSupplierPayout_ViewTotalDetails')->name('get.SupplierPayout_ViewTotalDetails');
    Route::get('/SupplierPayout_TotalClaimedVoucher', 'SupplierPayoutController@getSupplierPayout_TotalClaimedVoucher')->name('get.SupplierPayout_TotalClaimedVoucher');
    Route::get('/SupplierPayout_TotalPendingPayouts', 'SupplierPayoutController@getSupplierPayout_TotalPendingPayouts')->name('get.SupplierPayout_TotalPendingPayouts');
    Route::get('/SupplierPayout_TotalApprovedPayouts', 'SupplierPayoutController@getSupplierPayout_TotalApprovedPayouts')->name('get.SupplierPayout_TotalApprovedPayouts');
    Route::get('/SupplierPayout_TotalHoldVoucher', 'SupplierPayoutController@getSupplierPayout_TotalHoldVoucher')->name('get.SupplierPayout_TotalHoldVoucher');
    Route::post('/SaveSupplierPayout', 'SupplierPayoutController@saveSupplierPayout')->name('save.SupplierPayout');
    Route::post('/SubmitSupplierPayout', 'SupplierPayoutController@SubmitSupplierPayout')->name('submit.SupplierPayout');
    Route::post('/RemoveClaimedVoucher', 'SupplierPayoutController@removeClaimedVoucher')->name('remove.ClaimedVoucher');
    Route::post('/RemoveSupplierPayout', 'SupplierPayoutController@RemoveSupplierPayout')->name('remove.SupplierPayout');
});


