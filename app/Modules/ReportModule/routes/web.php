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

    /**
     * Fintech : No. of uploaded file and No. of disbursement file - Filter by date
     */
    Route::get('/rcef_rffa_reports/fintech-files', 'rffaReportController@fintech_files_custom_range')->name('reports.rffa_fintech_files');
    Route::get('/rcef_rffa_reports/fintech-files/today', 'rffaReportController@fintech_files_today')->name('reports.rffa_fintech_files_today');
    Route::get('/rcef_rffa_reports/fintech-files/yesterday', 'rffaReportController@fintech_files_yesterday')->name('reports.rffa_fintech_files_yesterday');
    Route::get('/rcef_rffa_reports/fintech-files/last_7_days', 'rffaReportController@fintech_files_last_7_days')->name('reports.rffa_fintech_files_last_7_days');
    Route::get('/rcef_rffa_reports/fintech-files/last_30_days', 'rffaReportController@fintech_files_last_30_days')->name('reports.rffa_fintech_files_last_30_days');
    Route::get('/rcef_rffa_reports/fintech-files/this_month', 'rffaReportController@fintech_files_this_month')->name('reports.rffa_fintech_files_this_month');
    Route::get('/rcef_rffa_reports/fintech-files/this_month_pt02', 'rffaReportController@fintech_files_this_month_pt02')->name('reports.rffa_fintech_files_this_month_pt02');
    Route::get('/rcef_rffa_reports/fintech-files/last_month', 'rffaReportController@fintech_files_last_month')->name('reports.rffa_fintech_files_last_month');

    /**
     * Fintech : No. of records uploaded and No. of records disbursed - Filter by date
     */
    Route::get('/rcef_rffa_reports/fintech-records', 'rffaReportController@fintech_records_custom_range')->name('reports.rffa_fintech_records');
    Route::get('/rcef_rffa_reports/fintech-records/today', 'rffaReportController@fintech_records_today')->name('reports.rffa_fintech_records_today');
    Route::get('/rcef_rffa_reports/fintech-records/yesterday', 'rffaReportController@fintech_records_yesterday')->name('reports.rffa_fintech_records_yesterday');
    Route::get('/rcef_rffa_reports/fintech-records/last_7_days', 'rffaReportController@fintech_records_last_7_days')->name('reports.rffa_fintech_records_last_7_days');
    Route::get('/rcef_rffa_reports/fintech-records/last_30_days', 'rffaReportController@fintech_records_last_30_days')->name('reports.rffa_fintech_records_last_30_days');
    Route::get('/rcef_rffa_reports/fintech-records/this_month', 'rffaReportController@fintech_records_this_month')->name('reports.rffa_fintech_records_this_month');
    Route::get('/rcef_rffa_reports/fintech-records/this_month_pt02', 'rffaReportController@fintech_records_this_month_pt02')->name('reports.rffa_fintech_records_this_month_pt02');
    Route::get('/rcef_rffa_reports/fintech-records/last_month', 'rffaReportController@fintech_records_last_month')->name('reports.rffa_fintech_records_last_month');

    /**
     * RFO or CO : By Region
     */
    Route::get('/rcef_rffa_reports', 'rffaReportController@rfo_or_co_program_focal_summary')->name('reports.rcef_rffa');

    /**
     * CO Program Focal: By Region and Province
     */
    Route::get('/rcef_rffa_reports/by_region_co_program_focal', 'rffaReportController@co_program_focal_summary_by_region')->name('reports.by_region_co_program_focal');

    /**
     * CO Program Focal: 
     * "Multi-Select Filters" => By Region and Province 
     */
    Route::get('/rcef_rffa_reports/by_region_co_program_focal/filter_region', 'ReportModuleController@get_region_without_province')->name('report.by_region_co_program_focal.get_reg_without_prov'); 
    Route::get('/rcef_rffa_reports/by_region_co_program_focal/filter_province', 'ReportModuleController@get_province_without_region')->name('report.by_region_co_program_focal.get_prov_without_reg');
    Route::get('/rcef_rffa_reports/by_region_co_program_focal/{reg_name}', 'ReportModuleController@get_province')->name('reports.by_region_co_program_focal.get_filter_province');
    Route::get('/rcef_rffa_reports/by_region_co_program_focal/region/{prov_name}', 'ReportModuleController@get_region')->name('reports.by_region_co_program_focal.get_filter_region');

    /**
     * CO Program Focal: By Region, Province, Municipality and Barangay
     */
    Route::get('/rcef_rffa_reports/by_region_province_municipality_and_barangay_co_program_focal', 'rffaReportController@co_program_focal_summary_by_region_province_municipality_and_barangay')->name('reports.co_program_focal_summary_by_region_province_municipality_and_barangay');    

    /**
     * CO Program Focal: 
     * "Multi-Select Filters" => By Region, Province, Municipality and Barangay
     */
    Route::get('/rcef_rffa_reports/by_region_province_municipality_and_barangay_co_program_focal/filter_region', 'ReportModuleController@get_region_without_province')->name('reports.co_program_focal_summary_by_region_province_municipality_and_barangay.get_reg_without_prov');    
    Route::get('/rcef_rffa_reports/by_region_province_municipality_and_barangay_co_program_focal/filter_province', 'ReportModuleController@get_province_without_region')->name('reports.co_program_focal_summary_by_region_province_municipality_and_barangay.get_prov_without_reg');    
    Route::get('/rcef_rffa_reports/by_region_province_municipality_and_barangay_co_program_focal/filter_city', 'ReportModuleController@get_municipality_without_province')->name('reports.co_program_focal_summary_by_region_province_municipality_and_barangay.get_mun_without_prov');    
    
    Route::get('/rcef_rffa_reports/by_region_province_municipality_and_barangay_co_program_focal/{reg_name}', 'ReportModuleController@get_province')->name('reports.co_program_focal_summary_by_region_province_municipality_and_barangay.get_filter_province');    
    Route::get('/rcef_rffa_reports/by_region_province_municipality_and_barangay_co_program_focal/region/{prov_name}', 'ReportModuleController@get_region')->name('reports.co_program_focal_summary_by_region_province_municipality_and_barangay.get_filter_region');    
    Route::get('/rcef_rffa_reports/by_region_province_municipality_and_barangay_co_program_focal/all/{reg_name?}/{prov_name?}', 'ReportModuleController@get_municipality')->name('reports.co_program_focal_summary_by_region_province_municipality_and_barangay.get_filter_municipality');    

    /**
     * RFO and CO: Report By Regional Approval Level
     */
    Route::get('/rcef_rffa_reports/report_by_regional_approval_level', 'rffaReportController@by_regional_approval')->name('reports.by_regional_approval');

    /**
     * RFO and CO: Report By Approval Level 
     */    
    Route::get('/rcef_rffa_reports/report_by_approval_level', 'rffaReportController@by_approval')->name('reports.by_approval');

    /**
     * CO Program Focal: 
     * "Multi-Select Filters" => By Region and Province 
     */
    Route::get('/rcef_rffa_reports/report_by_approval_level/filter_region', 'ReportModuleController@get_region_without_province')->name('reports.by_approval.get_reg_without_prov');
    Route::get('/rcef_rffa_reports/report_by_approval_level/filter_province', 'ReportModuleController@get_province_without_region')->name('reports.by_approval.get_prov_without_reg');
    Route::get('/rcef_rffa_reports/report_by_approval_level/{reg_name}', 'ReportModuleController@get_province')->name('reports.by_approval.get_filter_province');
    Route::get('/rcef_rffa_reports/report_by_approval_level/region/{prov_name}', 'ReportModuleController@get_region')->name('reports.by_approval.get_filter_region');

    /**
     * RFO and CO: Report By Advance Date Range
     */
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
