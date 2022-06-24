<?php

use Illuminate\Support\Facades\Route;

Route::group(array('module'=>'Login','namespace' => '\App\Modules\Login\Http\Controllers'), function() {
    // Forgot Password Screen
    Route::post('/api-v2/form_reset_password_link/sending_request', 'LoginController@send_btn_link_req_form')->name('send-request-password-link-mobile-app-v2');        
});

Route::group(["prefix" => "api-v2"],function(){
    Route::post('/login', 'MobileAppV2Controller@login')->name('mobile-login');
    Route::post('/search-voucher', 'MobileAppV2Controller@search_voucher')->name('search-voucher');
    Route::post('/verify-otp', 'MobileAppV2Controller@verify_otp')->name('mobile-verify-otp');
    Route::post('/resend-otp', 'MobileAppV2Controller@resend_otp')->name('mobile-resend-otp');
    Route::post('/get-transacted-vouchers', 'MobileAppV2Controller@get_transacted_vouchers')->name('get-transacted-vouchers');
    Route::post('/check-app-version', 'MobileAppV2Controller@check_app_version')->name('check-app-version');
    Route::post('/scan-qr-code', 'MobileAppV2Controller@scan_qr_code')->name('scan-qr-code');
    Route::post('/transact-voucher', 'MobileAppV2Controller@transact_voucher')->name('transact-voucher');
    Route::post('/payout-batch-list', 'MobileAppV2Controller@payout_batch_list')->name('payout-batch-list');
    Route::post('/update-attachments', 'MobileAppV2Controller@update_attachments')->name('update-attachments');
    Route::post('/update-cart', 'MobileAppV2Controller@update_cart')->name('update-cart');
    Route::post('/check-in-batch', 'MobileAppV2Controller@check_in_batch')->name('check-in-batch');
    

    
    
});



