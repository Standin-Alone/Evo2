<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\VouchersapprovedpayoutController;

Route::group([],function(){
    Route::resource('/Vouchersapprovedpayout','VouchersapprovedpayoutController');
    Route::get('/VoucherApprovedPayoutList','VouchersapprovedpayoutController@getVoucherApprovedPayoutList')->name('get.VoucherApprovedPayoutList');
});


