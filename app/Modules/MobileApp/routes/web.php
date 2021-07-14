<?php

Route::group([],function(){
// Login Screen
Route::post('/api/sign_in','MobileAppController@sign_in');

// QRCode Screen
Route::post('/api/get_voucher_info','MobileAppController@get_voucher_info');


// Home Screen
Route::get('/api/get-scanned-vouchers/{supplier_id}','MobileAppController@get_scanned_vouchers');

// Attachment Screen Claim Voucher (RRP)
Route::post('/api/submit-voucher-rrp','MobileAppController@submit_voucher_rrp');

//OTP Screen
Route::post('/api/resend-otp','MobileAppController@resendOTP');

Route::get('/api/get-items','MobileAppController@getProgramItems');
//  

Route::get('/otp','MobileAppController@otp');
});