<?php
use Illuminate\Support\Facades\Route;

Route::prefix('/report-module')->group(function () {
    // Route::get('/index','ReportModuleController@report_main')->name('reports.index');
    Route::get('/total-claim-by-region-province-and-supplier', 'ReportModuleController@total_claim_vouchers')->name('report.total_claim_vouchers');
    
    Route::get('/claimed-not-yet-paid', 'ReportModuleController@claimed_not_yet_paid')->name('report.claimed_not_yet_paid');

    Route::get('/total-claim-by-region-province-and-supplier/filter_province', 'ReportModuleController@get_province_without_region')->name('report.get_prov_without_reg');
    Route::get('/total-claim-by-region-province-and-supplier/filter_region', 'ReportModuleController@get_region_without_province')->name('report.get_reg_without_prov');   

    Route::get('/claimed-not-yet-paid/filter_province', 'ReportModuleController@get_province_without_region')->name('report.not_paid.get_prov_without_reg');
    Route::get('/claimed-not-yet-paid/filter_region', 'ReportModuleController@get_region_without_province')->name('report.not_paid.get_reg_without_prov');    

    Route::get('/total-claim-by-region-province-and-supplier/{reg_name}', 'ReportModuleController@get_province')->name('report.get_filter_province');
    Route::get('/total-claim-by-region-province-and-supplier/region/{prov_name}', 'ReportModuleController@get_region')->name('report.get_filter_region');
    
    Route::get('/claimed-not-yet-paid/{reg_name}', 'ReportModuleController@get_province')->name('report.not_paid.get_filter_province');
    Route::get('/claimed-not-yet-paid/region/{prov_name}', 'ReportModuleController@get_region')->name('report.not_paid.get_filter_region');

    Route::get('/total-number-of-ready-vouchers-by-region-province-and-suppliers', 'ReportModuleController@ready_vouchers')->name('report.total_ready_vouchers');

    Route::get('/total-number-of-ready-vouchers-by-region-province-and-suppliers/filter_province', 'ReportModuleController@get_province_without_region')->name('report.ready_voucher.get_prov_without_reg');
    Route::get('/total-number-of-ready-vouchers-by-region-province-and-suppliers/filter_region', 'ReportModuleController@get_region_without_province')->name('report.ready_voucher.get_reg_without_prov');

    Route::get('/total-number-of-ready-vouchers-by-region-province-and-suppliers/{reg_name}', 'ReportModuleController@get_province')->name('report.ready_voucher.get_filter_province');
    Route::get('/total-number-of-ready-vouchers-by-region-province-and-suppliers/region/{prov_name}', 'ReportModuleController@get_region')->name('report.ready_voucher.get_filter_region');

    Route::get('/summary-claims-by-supplier', 'ReportModuleController@report_summary')->name('reports.summary');

    Route::get('/rcef_rffa_reports', 'rffaReportController@rfo_or_co_program_focal_summary')->name('reports.rcef_rffa');
    Route::get('/rcef_rffa_reports/by_region_co_program_focal', 'rffaReportController@co_program_focal_summary_by_region')->name('reports.by_region_co_program_focal');
    Route::get('/rcef_rffa_reports/by_region_province_municipality_and_barangay_co_program_focal', 'rffaReportController@co_program_focal_summary_by_region_province_municipality_and_barangay')->name('reports.co_program_focal_summary_by_region_province_municipality_and_barangay');    
    Route::get('/rcef_rffa_reports/report_by_approval_level', 'rffaReportController@by_approval')->name('reports.by_approval');
    Route::get('/rcef_rffa_reports/daily', 'rffaReportController@custom_range')->name('reports.rffa_custom_range');
    Route::get('/rcef_rffa_reports/monthly', 'rffaReportController@monthly')->name('reports.rffa_monthly');
    Route::get('/rcef_rffa_reports/today', 'rffaReportController@today')->name('reports.rffa_today');
    Route::get('/rcef_rffa_reports/yesterday', 'rffaReportController@yesterday')->name('reports.rffa_yesterday');
    Route::get('/rcef_rffa_reports/last_7_days', 'rffaReportController@last_7_days')->name('reports.rffa_last_7_days');
    Route::get('/rcef_rffa_reports/last_30_days', 'rffaReportController@last_30_days')->name('reports.rffa_last_30_days');
    Route::get('/rcef_rffa_reports/this_month', 'rffaReportController@this_month')->name('reports.rffa_this_month');
    Route::get('/rcef_rffa_reports/last_month', 'rffaReportController@last_month')->name('reports.rffa_last_month');
});

// Route::get('report-module', 'ReportModuleController@welcome');
