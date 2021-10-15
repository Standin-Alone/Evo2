<?php

use Illuminate\Support\Facades\Route;

Route::resource('/execution-monitoring', 'SummaryMonitoringController');
Route::get('/get-funds-liquidation', 'SummaryMonitoringController@get_funds_liquidation')->name('get-funds-liquidation');
Route::get('/get-uploaded-disbursed-data', 'SummaryMonitoringController@get_upload_disbursed_data')->name('get-uploaded-disbursed-data');
Route::get('/get-pie-data', 'SummaryMonitoringController@get_pie_data')->name('get-pie-data');
Route::get('/get-rfo-transaction-data', 'SummaryMonitoringController@get_rfo_transactions_data')->name('get-rfo-transactions-data');