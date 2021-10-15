<?php
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\SupplierPayoutController;

Route::group([],function(){
    Route::resource('/SupplierPayout','SupplierPayoutController');
    Route::get('/SupplierClaimedVoucher', 'SupplierPayoutController@getSupplierClaimedVoucher')->name('get.SupplierPayout_ClaimedVoucherDetails');
    Route::get('/SupplierPayoutContent', 'SupplierPayoutController@getSupplierPayout')->name('get.SupplierPayoutList');
    Route::get('/BatchPayoutDropList', 'SupplierPayoutController@getBatchPayoutDropList')->name('get.BatchPayoutDropList');
    Route::get('/batchpayoutdetails', 'SupplierPayoutController@getBatchpayoutdetails')->name('get.SupplierBatchPayoutDetails');
    Route::get('/HoldTransactionDetails', 'SupplierPayoutController@getHoldTransactionDetails')->name('get.SupplierPayout_HoldTransDetails');
    Route::get('/StatusBatchPayout', 'SupplierPayoutController@getStatusBatchPayout')->name('get.SupplierPayout_Status');
    Route::get('/HoldTransAttachmentsImg', 'SupplierPayoutController@getHoldTransAttachmentsImg')->name('get.SupplierPayoutAttachmentsImg');

    Route::post('/SaveSupplierPayout', 'SupplierPayoutController@saveSupplierPayout')->name('save.SupplierPayout');
    Route::post('/SubmitSupplierPayout', 'SupplierPayoutController@SubmitSupplierPayout')->name('submit.SupplierPayout');
    Route::post('/RemoveSupplierPayout', 'SupplierPayoutController@RemoveSupplierPayout')->name('remove.SupplierPayout');
});


