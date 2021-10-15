<?php
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\PayoutMonitoringController;

Route::group([],function(){
    Route::resource('/PayoutMonitoring','PayoutMonitoringController');
    Route::get('/PayoutMonitoringContent', 'PayoutMonitoringController@getPayoutMonitoringContent')->name('get.PayoutMonitoringContent');
    Route::get('/PayoutMonitoringDetails', 'PayoutMonitoringController@getPayoutMonitoringDetails')->name('get.PayoutMonitoringDetails');
    Route::get('/PayoutMonitoringAttachImg', 'PayoutMonitoringController@getPayoutMonitoringAttachImg')->name('get.PayoutMonitoringAttachImg');

});


