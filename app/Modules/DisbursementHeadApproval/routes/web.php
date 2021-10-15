<?php
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\DisbursementHeadApprovalController;

Route::group([],function(){
    Route::resource('/DisbursementHeadApproval','DisbursementHeadApprovalController');
    Route::get('/HeadApprovalList', 'DisbursementHeadApprovalController@getHeadApprovalList')->name('get.HeadApprovalList');
    Route::get('/DBPBatchDownload', 'DisbursementHeadApprovalController@downloadDBPBatch')->name('download.DBPBatchDownload');
    Route::get('/DBPapproveddHistoryList', 'DisbursementHeadApprovalController@getDBPapproveddHistoryList')->name('get.DBPapproveddHistoryList');
    
    Route::post('/ApproveHeadApproval', 'DisbursementHeadApprovalController@ApproveHeadApproval')->name('approve.HeadApproval');
    Route::post('/HeadApprovalPin', 'DisbursementHeadApprovalController@validateHeadApprovalPin')->name('validate.HeadApprovalPin');
    
   });


