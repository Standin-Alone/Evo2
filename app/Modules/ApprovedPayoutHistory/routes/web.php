<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ApprovedPayoutHistoryController;

Route::group([],function(){
    Route::resource('/ApprovedPayoutHistory','ApprovedPayoutHistoryController');
    Route::get('/ApprovedPayoutHistoryList','ApprovedPayoutHistoryController@getApprovedPayoutHistoryList')->name('get.ApprovedPayoutHistoryList');
    Route::get('/ApprovedPayoutHistoryDetails', 'ApprovedPayoutHistoryController@getApprovedPayoutHistoryDetails')->name('get.ApprovedPayoutHistoryDetails');
    Route::get('/ApprovedPayoutAttachmentsImg', 'ApprovedPayoutHistoryController@getApprovedPayoutAttachmentsImg')->name('get.ApprovedPayoutAttachmentsImg');

    Route::post('/BudgetSupervisorApproval', 'ApprovedPayoutHistoryController@approveBudgetSupervisorApproval')->name('approve.BudgetSupervisorApproval');
    Route::post('/DisburstmentSupervisorApproval', 'ApprovedPayoutHistoryController@approveDisburstmentSupervisorApproval')->name('approve.DisburstmentSupervisorApproval');
});
