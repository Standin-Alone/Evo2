<?php

use Illuminate\Support\Facades\Route;

Route::group(["prefix" => "api-v2"],function(){
    Route::post('/login', 'MobileAppV2Controller@login')->name('mobile-login');
    Route::post('/verify-otp', 'MobileAppV2Controller@verify_otp')->name('mobile-verify-otp');
    Route::post('/resend-otp', 'MobileAppV2Controller@resend_otp')->name('mobile-resend-otp');
    Route::post('/get-transacted-vouchers', 'MobileAppV2Controller@get_transacted_vouchers')->name('get-transacted-vouchers');
    Route::post('/check-app-version', 'MobileAppV2Controller@check_app_version')->name('check-app-version');
    Route::post('/scan-qr-code', 'MobileAppV2Controller@scan_qr_code')->name('scan-qr-code');
    Route::post('/transact-voucher', 'MobileAppV2Controller@transact_voucher')->name('transact-voucher');
    Route::post('/payout-batch-list', 'MobileAppV2Controller@payout_batch_list')->name('payout-batch-list');
});



