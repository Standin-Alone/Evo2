<?php

use Illuminate\Support\Facades\Route;

Route::get('rfo-approval-module', 'RfoApprovalModuleController@welcome');

Route::prefix('/rfo-approval-module')->group(function () {

    Route::get('/index', 'RfoApprovalModuleController@main_page')->name('rfo_approval_module.index');

    Route::get('/view/account-activation','RfoApprovalModuleController@list_of_request_for_account_activation')->name('rfo_approval_module.account_activation');

    Route::get('/view/approved-checklist', 'RfoApprovalModuleController@view_approved_checklists')->name('rfo_approval_module.view_approved_checklists');

    Route::get('/view/user-account-setup-role-and-program', 'RfoApprovalModuleController@setup_program_for_users')->name('rfo_approval_module.user_account_setup_view');

    Route::get('/view/supplier-branch-approval','RfoApprovalModuleController@list_of_request_for_main_branch_approval')->name('rfo_approval_module.main_branch_approval');

    Route::get('/view/list-of-suppliers-and-merchants','RfoApprovalModuleController@list_of_merchants_and_supplier')->name('rfo_approval_module.list_of_merchs_and_supps');

    Route::get('/view/list-of-suppliers-and-merchants/current-supplier/{supplier_id?}', 'RfoApprovalModuleController@show_current_supplier_profile')->name('rfo_approval_module.show_current_supplier_profile');

    Route::get('/view/list-of-suppliers-and-merchants/temp-supplier/{supplier_id?}', 'RfoApprovalModuleController@view_temp_supplier_profile')->name('rfo_approval_module.view_temp_supplier_profile');

    // Route::patch('/view/account_activation/update-status', 'RfoApprovalModuleController@udapte_account_activation_status')->name('rfo_approval_module.update_user_status');

    // Route::get('/view/account_activation/{user_id}/{first_name}/{middle_name}/{last_name}/{ext_name}/{status}','RfoApprovalModuleController@list_of_request_for_account_activation')->name('rfo_approval_module.account_activation_details');

    Route::get('/view/account-activation/view-checked-requirements','RfoApprovalModuleController@view_already_checked_requirements_on_account_activation')->name('rfo_approval_module.view_already_checked_requirements_on_account_activation');

    Route::post('/view/user-account-setup-role-and-program/create', 'RfoApprovalModuleController@create_setup_role_and_account')->name('rfo_approval_module.create_setup_role_and_account');
    
    Route::patch('/view/account-activation/save-user-checklist-details', 'RfoApprovalModuleController@create_user_checklist_details')->name('rfo_approval_module.create_user_checklist_details');

    Route::GET('/view/create/selected_supplier_group', 'RfoApprovalModuleController@selected_supplier_group')->name('rfo_approval_module.selected_supplier_group');

    Route::patch('/view/supplier-branch-approval/update-approval-status', 'RfoApprovalModuleController@update_supplier_main_branch_approval_status')->name('rfo_approval_module.update_supplier_main_branch_approval_status');
});

// // Certificate of Accreditation and SRN Verfying Authentication
//     Route::get('/verify-accreditation/{srn?}', 'RfoApprovalModuleController@view_verify_cert')->name('rfo_approval_module.qr_verify_cert');

//     // success page
//     Route::get('/success', 'RfoApprovalModuleController@verified_cert_success')->name('verified_success_page');

//     // error page
//     Route::get('/404_page', 'RfoApprovalModuleController@page_404_status')->name('verified_page_not_found');


// First Update Profile   
    // Get selected region 
    Route::get('/supplier-profile/head-supplier/geo_map/{reg_code?}', 'HeadSupplierProfileController@get_supplier_province')->name('rfo_approval_module.supplier_province');

    // Get selected Province
    Route::get('/supplier-profile/head-supplier/geo_map/{reg_code?}/{prov_code?}', 'HeadSupplierProfileController@get_supplier_city')->name('rfo_approval_module.supplier_city');

    // Get selected municipality
    Route::get('/supplier-profile/head-supplier/geo_map/{reg_code?}/{prov_code?}/{mun_code?}', 'HeadSupplierProfileController@get_supplier_barangay')->name('rfo_approval_module.supplier_barangay');

    // Link for continuing the Supplier Profile ("First Update")
    Route::get('/supplier-profile/head-supplier/{user_id?}/{code?}', 'HeadSupplierProfileController@continuation_of_creation_for_supplier_profile_view')->name('supplier_profile_module.create_supplier_profile_view');

    // Update or Insert to temp_supplier table when created
    Route::post('/supplier-profile/head-supplier/send-latest-update-supplier-profile', 'HeadSupplierProfileController@send_update_profile')->name('supplier_profile_module.send_update_profile');

    // Patch: Update confirm supplier profile
    Route::patch('/supplier-profile/head-supplier/update-confirmed-supplier-profile', 'HeadSupplierProfileController@update_supplier_profile')->name('supplier_profile_module.update_supplier_profile');

    // Route::get('/supplier-profile/head-supplier/approve-update-profile/{supplier_id?}/{supplier_group?}/{supplier_name?}/{address?}/{reg?}/{prv?}/{mun?}/{brgy?}/{business_permit?}/{email?}/{contact?}/{fname?}/{mname?}/{lname?}/{ename?}{bank_long_name?}/{bank_short_name?}/{bank_acc_no?}/{bank_acc_name?}/{phone_no?}', 'HeadSupplierProfileController@update_supplier_profile')->name('rfo_approval_module.update_supplier_profile');

    // Route::get('/supplier-profile/update-success', 'HeadSupplierProfileController@first_profie_update_success_page')->name('update_success_page');

    // Route::get('/supplier-profile/head-supplier/404-page-not-found', 'HeadSupplierProfileController@page_404_status')->name('supplier_profile_module.update_profile_page_not_found');

// Route::get('/supplier-profile/head-supplier/update-complete', 'HeadSupplierProfileController@approved_update_complete')->name('supplier_profile_module.approve_img_page');

// Route::post('/SupplierProfile/create', 'SupplierProfileController@create_supplier_profile')->name('supplier_profile_module.create_supplier_profile');

// Update supplier profile when the button approve are click
// Route::patch('/supplier-profile/request-update/{supplier_id?}/{fname?}/{mname?}/{lname?}/{ename?}/{email?}/{contact_no?}/{address?}/{bank_name?}/{bank_acc_name?}/{bank_acc_no?}/{supplier_profile_update_status?}')->name('rfo_approval_module.update_supplier_profile');
