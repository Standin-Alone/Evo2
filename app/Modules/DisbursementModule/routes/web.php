<?php
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\DisbursementModuleController;

Route::group([],function(){
    Route::resource('/DisbursementModule','DisbursementModuleController');
    Route::get('/DisbursementList', 'DisbursementModuleController@getDisbursementList')->name('get.DisbursementList');
    Route::get('/EndorseMunList', 'DisbursementModuleController@getEndorseMunList')->name('get.EndorseMunList');
    Route::get('/ApprovedBatchDisbursement', 'DisbursementModuleController@getApprovedBatchDisbursement')->name('get.ApprovedBatchDisbursement');
    Route::get('/ApprovedDisbursementList', 'DisbursementModuleController@getApprovedDisbursementList')->name('get.ApprovedDisbursementList');
    Route::get('/ApprovedDisbursementHistory', 'DisbursementModuleController@getApprovedDisbursementHistory')->name('get.ApprovedDisbursementHistory');
    Route::get('/DisbursementApprovalProvinceList', 'DisbursementModuleController@getDisbursementApprovalProvinceList')->name('get.DisbursementApprovalProvinceList');
    Route::get('/FilteredProvinceList', 'DisbursementModuleController@getFilteredProvinceList')->name('get.FilteredProvinceList');
    Route::get('/DisbursementFundSource', 'DisbursementModuleController@getDisbursementFundSource')->name('get.DisbursementFundSource');
    Route::get('/DisbursementkycDetails', 'DisbursementModuleController@getDisbursementkycDetails')->name('get.DisbursementkycDetails');
    Route::get('/ApproveddDisbursementHistory', 'DisbursementModuleController@getApproveddDisbursementHistory')->name('get.ApproveddDisbursementHistory');

    Route::get('/DisbursementTotalReadyforBatch', 'DisbursementModuleController@getDisbursementTotalReadyforBatch')->name('get.DisbursementTotalReadyforBatch');
    Route::get('/DisbursementTotalReadyforApproval', 'DisbursementModuleController@getBeneficiariesforApproval')->name('get.BeneficiariesforApproval');
    Route::get('/DisbursementTotalApproved', 'DisbursementModuleController@getDisbursementTotalApproved')->name('get.DisbursementTotalApproved');

    Route::get('/NewlyUploaded', 'DisbursementModuleController@getNewlyUploaded')->name('get.NewlyUploaded');

    Route::get('/BeneficiariesExcel', 'DisbursementModuleController@downloadBeneficiariesExcel')->name('download.BeneficiariesExcel');
    Route::get('/GenerateBeneficiariesExcel', 'DisbursementModuleController@GenerateBeneficiariesExcel')->name('generate.BeneficiariesExcel');
   
    Route::post('/DisbursementApproval', 'DisbursementModuleController@approveDisbursement')->name('approve.Disbursement');
    Route::post('/DisbursementBeneficiariesRemove', 'DisbursementModuleController@removeDisbursementBeneficiaries')->name('remove.DisbursementBeneficiaries');
    Route::post('/DisbursementBeneficiariesActivate', 'DisbursementModuleController@activateDisbursementBeneficiaries')->name('activate.DisbursementBeneficiaries');    
    Route::get('/DisbursementFilteredMunList', 'DisbursementModuleController@getDisbursementFilteredMunList')->name('get.DisbursementFilteredMunList');
});



