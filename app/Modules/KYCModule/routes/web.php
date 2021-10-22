<?php

use Illuminate\Support\Facades\Route;


Route::get('/kyc', 'KYCModuleController@index')->name('kyc.index');
Route::get('/kyc-reports', 'KYCModuleController@report_index')->name('kyc-reports-index');
Route::get('/kyc/file-data-reports', 'KYCModuleController@file_data_reports')->name('kyc-file-data-reports');
Route::get('/kyc/today-reports', 'KYCModuleController@kyc_card_summary_today')->name('kyc-today-reports');
Route::get('/kyc/show', 'KYCModuleController@show')->name('kyc.show');
Route::post('/kyc/import', 'KYCModuleController@import')->name('import-kyc');
Route::get('/kyc/summary-files-report', 'KYCModuleController@summary_files_report')->name('kyc-summary-files-report');



// reports
Route::get('/kyc/all-reports', 'KYCModuleController@kyc_card_summary_all')->name('kyc-all-reports');
Route::get('/kyc/region-fintech-reports', 'KYCModuleController@region_fintech_reports')->name('kyc-region-fintech-reports');





