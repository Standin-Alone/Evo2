<?php
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\BatchPayoutController;

Route::group([],function(){
    Route::resource('/BatchPayout','BatchPayoutController');
    Route::get('/BatchPayoutContent','BatchPayoutController@getBatchPayoutContent')->name('get.BatchPayoutList');
    Route::post('/createBatchPayout', 'BatchPayoutController@createBatchPayout')->name('create.batchpayout');
    Route::post('/updateBatchPayout', 'BatchPayoutController@updateBatchPayout')->name('update.batchpayout');
    Route::post('/removeBatchPayout', 'BatchPayoutController@removeBatchPayout')->name('remove.batchpayout');
});


