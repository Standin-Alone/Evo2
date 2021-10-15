<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\VoucherspendingpayoutController;

Route::group([],function(){
    Route::resource('/Voucherspendingpayout','VoucherspendingpayoutController');
    Route::get('/VoucherPendingPayoutList','VoucherspendingpayoutController@getVoucherPendingPayoutList')->name('get.VoucherPendingPayoutList');
});


