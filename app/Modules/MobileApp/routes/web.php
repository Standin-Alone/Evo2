<?php

Route::group([],function(){
// Login Screen
Route::post('/api/sign_in','MobileAppController@sign_in');

// QRCode Screen
Route::post('/api/get_voucher_info','MobileAppController@get_voucher_info');


// Home Screen
Route::get('/api/get-scanned-vouchers/{supplier_id}','MobileAppController@get_scanned_vouchers');
Route::get('/api/get-transaction-history/{reference_id}','MobileAppController@get_transactions_history');
Route::get('/api/get-transacted-items/{reference_id}','MobileAppController@getTransactedItems');

// Attachment Screen Claim Voucher (RRP)
Route::post('/api/submit-voucher-rrp','MobileAppController@submit_voucher_rrp');


// Attachment Screen Claim Voucher (CFSMFF)
Route::post('/api/submit-voucher-cfsmff','MobileAppController@submit_voucher_cfsmff');


//OTP Screen
Route::post('/api/resend-otp','MobileAppController@resendOTP');

Route::get('/api/get-items','MobileAppController@getProgramItems');

Route::post('/api/validate-otp','MobileAppController@validateOTP');
//  

Route::get('/otp','MobileAppController@otp');
});