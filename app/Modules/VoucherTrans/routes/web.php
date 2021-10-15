<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\VoucherTransController;

Route::group([],function(){
    Route::resource('/VoucherTrans','VoucherTransController');
    Route::get('/VoucherTransList','VoucherTransController@getVoucherTransList')->name('get.VoucherTransList');
    Route::get('/VoucherListAttachments','VoucherTransController@getVoucherListAttachments')->name('get.VoucherListAttachments');
});


