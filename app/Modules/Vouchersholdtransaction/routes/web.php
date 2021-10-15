<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\VouchersholdtransactionController;

Route::group([],function(){
    Route::resource('/Vouchersholdtransaction','VouchersholdtransactionController');
    Route::get('/VoucherHoldTransactionList','VouchersholdtransactionController@getVoucherHoldTransactionList')->name('get.VoucherHoldTransactionList');
});


