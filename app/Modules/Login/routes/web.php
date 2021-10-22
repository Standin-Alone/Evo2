<?php
use Illuminate\Support\Facades\Route;

/**
 * Login
 */
Route::get('/', 'LoginController@go_to_login');

Route::get('/login', 'LoginController@index')->name('main.page');
Route::post('/login/verify', 'LoginController@login_action')->name('user.login');
Route::get('/login/otp_page_form/{uuid}', 'OTPController@otp_page')->name('otp_page');
Route::post('/login/otp_page_form/otp_verify', 'OTPController@verify_OTP_form')->name('form.check_otp_verification');
Route::patch('/login/otp_page_form/reset_otp', 'OTPController@resend_otp')->name('reset_otp_link');

/**
 * Password
 */
Route::get('/form_reset_password_link', 'LoginController@show_link_request_form')->name('form.confirmation.reset.password');
Route::post('/form_reset_password_link/sending_request', 'LoginController@send_btn_link_req_form')->name('send.req.pwd.link');
Route::get('/create_new_password/{uuid}', 'LoginController@change_password_form')->name('user.change.password');
Route::patch('/create_new_password/{uuid}/update', 'LoginController@update_password')->name('update.password');

/**
 * Logout
 */
Route::get('/logout', 'LoginController@logout_action')->name('user.logout');
