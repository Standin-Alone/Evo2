<?php
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\BatchManagementController;

Route::group([],function(){
    Route::resource('/BatchManagement','BatchManagementController');
    Route::get('/DisbursementList', 'BatchManagementController@getDisbursementList')->name('get.DisbursementList');
    Route::get('/ApprovedBatchDisbursement', 'BatchManagementController@getApprovedBatchDisbursement')->name('get.ApprovedBatchDisbursement');
    Route::get('/ApprovedDisbursementList', 'BatchManagementController@getApprovedDisbursementList')->name('get.ApprovedDisbursementList');
    Route::get('/ApprovedDisbursementHistory', 'BatchManagementController@getApprovedDisbursementHistory')->name('get.ApprovedDisbursementHistory');
    Route::get('/DisbursementApprovalProvinceList', 'BatchManagementController@getDisbursementApprovalProvinceList')->name('get.DisbursementApprovalProvinceList');
    Route::get('/FilteredProvinceList', 'BatchManagementController@getFilteredProvinceList')->name('get.FilteredProvinceList');
    Route::get('/DisbursementFundSource', 'BatchManagementController@getDisbursementFundSource')->name('get.DisbursementFundSource');
    Route::get('/DisbursementkycDetails', 'BatchManagementController@getDisbursementkycDetails')->name('get.DisbursementkycDetails');
    Route::get('/ApproveddDisbursementHistory', 'BatchManagementController@getApproveddDisbursementHistory')->name('get.ApproveddDisbursementHistory');

    Route::get('/DisbursementTotalReadyforBatch', 'BatchManagementController@getDisbursementTotalReadyforBatch')->name('get.DisbursementTotalReadyforBatch');
    Route::get('/DisbursementTotalReadyforApproval', 'BatchManagementController@getBeneficiariesforApproval')->name('get.BeneficiariesforApproval');
    Route::get('/DisbursementTotalApproved', 'BatchManagementController@getDisbursementTotalApproved')->name('get.DisbursementTotalApproved');

    Route::get('/NewlyUploaded', 'BatchManagementController@getNewlyUploaded')->name('get.NewlyUploaded');

    Route::get('/BeneficiariesExcel', 'BatchManagementController@downloadBeneficiariesExcel')->name('download.BeneficiariesExcel');
    Route::get('/GenerateBeneficiariesExcel', 'BatchManagementController@GenerateBeneficiariesExcel')->name('generate.BeneficiariesExcel');
   
    Route::post('/DisbursementApproval', 'BatchManagementController@approveDisbursement')->name('approve.Disbursement');
    Route::post('/DisbursementBeneficiariesRemove', 'BatchManagementController@removeDisbursementBeneficiaries')->name('remove.DisbursementBeneficiaries');
    Route::post('/DisbursementBeneficiariesActivate', 'BatchManagementController@activateDisbursementBeneficiaries')->name('activate.DisbursementBeneficiaries');    
});



