<?php

use Illuminate\Support\Facades\Route;


// Route::get('/kyc', 'KYCModuleController@index')->name('kyc.index');
Route::get('/kyc', 'KYCModuleController@uploading_index')->name('kyc.index')->middleware('ensure.user.has.role');
Route::get('/kyc-reports', 'KYCModuleController@report_index')->name('kyc-reports-index');
Route::get('/kyc/file-data-reports', 'KYCModuleController@file_data_reports')->name('kyc-file-data-reports');
Route::get('/kyc/today-reports', 'KYCModuleController@kyc_card_summary_today')->name('kyc-today-reports');
Route::get('/kyc/show', 'KYCModuleController@show')->name('kyc.show');
Route::post('/kyc/import', 'KYCModuleController@import')->name('import-kyc');
Route::get('/kyc/summary-files-report', 'KYCModuleController@summary_files_report')->name('kyc-summary-files-report');



// reports
Route::get('/kyc-profiles', 'KYCModuleController@kyc_profiles_report_index')->name('kyc-profiles-report-index');
Route::get('/kyc/all-reports', 'KYCModuleController@kyc_card_summary_all')->name('kyc-all-reports');
Route::get('/kyc/region-fintech-reports', 'KYCModuleController@region_fintech_reports')->name('kyc-region-fintech-reports');
Route::get('/disbursement-generated-reports', 'KYCModuleController@disbursement_generated_reports')->name('disbursement-generated-reports');
Route::get('disbursement-generated-show-more/{dbp_batch_id}', 'KYCModuleController@disbursement_generated_show_more')->name('disbursement-generated-show-more');
Route::get('/list_of_generated_disbursement_by_file_name', 'KYCModuleController@list_of_generated_disbursement_by_file_name')->name('list-of-generated-disbursement-by-file-name');




// new file upload

Route::post('/kyc/upload-file-only', 'KYCModuleController@upload_file_only')->name('upload-file-only');
Route::get('/kyc/get-ingest-files', 'KYCModuleController@get_to_ingest_files')->name('get-ingest-files');
Route::get('/kyc/get-ingested-files', 'KYCModuleController@get_ingested_files')->name('get-ingested-files');
Route::post('/kyc/ingest-file', 'KYCModuleController@ingest_file')->name('ingest-file');
Route::post('/kyc/update-agency', 'KYCModuleController@update_agency')->name('update-agency');
Route::post('/kyc/remove-uploaded-records', 'KYCModuleController@remove_uploaded_records')->name('remove-uploaded-records');
Route::post('/kyc/kyc-get-error-logs', 'KYCModuleController@kyc_get_error_logs')->name('kyc-get-error-logs');


