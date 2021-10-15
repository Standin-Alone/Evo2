<?php
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\DBPapprovalController;

Route::group([],function(){
    Route::resource('/DBPapproval','DBPapprovalController');
    Route::get('/DBPapprovalList', 'DBPapprovalController@getDBPapprovalList')->name('get.DBPapprovalList');
    Route::get('/DBPBatchDownload', 'DBPapprovalController@downloadDBPBatch')->name('download.DBPBatchDownload');
    Route::get('/DBPapproveddHistoryList', 'DBPapprovalController@getDBPapproveddHistoryList')->name('get.DBPapproveddHistoryList');

    Route::post('/ApproveDBPapproval', 'DBPapprovalController@ApproveDBPapproval')->name('approve.DBPapproval');
    Route::post('/DBPApprovalPin', 'DBPapprovalController@validateDBPApprovalPin')->name('validate.DBPApprovalPin');
});


