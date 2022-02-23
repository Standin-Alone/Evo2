<?php



// Route::group(array('module'=>'Login','namespace' => 'App\Modules\Login\Http\Controllers'), function() {
//     // Forgot Password Screen
//     Route::post('/api/form_reset_password_link/sending_request', 'LoginController@send_btn_link_req_form')->name('send-request-password-link-mobile-app');
// });


Route::group([],function(){
Route::post('/api/discard_transaction','MobileAppController@discard_transaction');

Route::get('/api/get-notifications','MobileAppController@get_notifications');
Route::get('/api/set-notifications','MobileAppController@set_notifications');


// Login Screen
Route::post('/api/sign_in','MobileAppController@sign_in');





// QRCode Screen
Route::post('/api/get_voucher_info','MobileAppController@get_voucher_info');

// Authentication

Route::get('/api/check_utility/{version}','MobileAppController@check_utility');

// Home Screen
Route::get('/api/get-scanned-vouchers/{supplier_id}/{offset}','MobileAppController@get_scanned_vouchers');
Route::get('/api/get-scanned-vouchers-today/{supplier_id}/{offset}','MobileAppController@get_scanned_vouchers_today');
Route::get('/api/get-transaction-history/{reference_id}','MobileAppController@get_transactions_history');
Route::get('/api/get-transacted-items/{reference_id}','MobileAppController@getTransactedItems');



// cart modifying function
Route::post('/api/check-if-category-has-draft','MobileAppController@check_if_category_has_draft');
Route::post('/api/save-to-cart','MobileAppController@save_added_to_cart');
Route::post('/api/check-draft-transaction','MobileAppController@check_draft_transaction');
Route::post('/api/delete-cart','MobileAppController@delete_cart');
Route::post('/api/checkout-update-cart','MobileAppController@checkout_update_cart');



// Attachment Screen Claim Voucher (RRP)
Route::post('/api/submit-voucher-rrp','MobileAppController@submit_voucher_rrp');


// Attachment Screen Claim Voucher (CFSMFF)
Route::post('/api/submit-voucher','MobileAppController@submit_voucher');


//OTP Screen
Route::post('/api/resend-otp','MobileAppController@resendOTP');

Route::get('/api/get-items','MobileAppController@getProgramItems');

Route::post('/api/validate-otp','MobileAppController@validateOTP');
//  

Route::get('/otp','MobileAppController@otp');

Route::get('/api/get-time','MobileAppController@get_time');
});