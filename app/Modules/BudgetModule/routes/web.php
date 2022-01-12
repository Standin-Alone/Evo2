<?php

use Illuminate\Support\Facades\Route;
Route::prefix('/budget')->group(function () {
    Route::get('/fund-source-encoding', 'BudgetModuleController@fund_source_encoding_view')->name('fund_encoding');
    
    Route::get('/fund-source-encoding/{reg_code?}', 'BudgetModuleController@get_province')->name('fund_region');
    
    Route::post('/fund-source-encoding/submit_form', 'BudgetModuleController@fund_encoding_ors')->name('submit_encoding_form');
    
    Route::get('/fund-monitoring-and-disbursement','BudgetModuleController@fund_monitoring_and_disbursement_view')->name('fund_moni_and_disb');
    
    // Rffa fund breakdown (modal)
    Route::get('/fund-monitoring-and-disbursement/rffa/{fund_id?}','RffaBudgetController@rffa_disburse_breakdown')->name('rffa_fund_disburse_breakdown');
    
    // Voucher fund breakdown (modal)
    Route::get('/fund-monitoring-and-disbursement/view-fund-source-breakdown/{fund_id?}/{program?}', 'BudgetModuleController@get_fund_source_breakdown')->name('voucher_fund_disburse_breakdown');  
    
    Route::get('/fund-overview', 'BudgetModuleController@fund_overview')->name('fund_overview');
    
    Route::patch('/fund-source-overview/submit_form', 'BudgetModuleController@update_amount_overview')->name('fund_update_amount');
});

