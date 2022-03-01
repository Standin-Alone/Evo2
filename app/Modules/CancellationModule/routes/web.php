<?php
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\CancellationModuleController;

Route::group([],function(){
    Route::resource('/CancellationModule','CancellationModuleController');
    Route::get('/CancellationModuleList','CancellationModuleController@getCancellationModuleList')->name('get.CancellationModuleList');
    Route::get('/CancellationModuleDetails','CancellationModuleController@getCancellationModuleDetails')->name('get.CancellationModuleDetails');
    Route::get('/GeneratedCancellationModule','CancellationModuleController@getGeneratedCancellationModule')->name('get.GeneratedCancellationModule');
    Route::get('/DownloadCancellationModuleTexfile','CancellationModuleController@downloadCancellationModuleTexfile')->name('download.CancellationModuleTexfile');
    Route::get('/GeneratedFileDetails','CancellationModuleController@getGeneratedFileDetails')->name('get.GeneratedFileDetails');

    Route::post('/SubmitCancellationModuleDetails','CancellationModuleController@submitCancellationModuleDetails')->name('submit.CancellationModuleDetails');
});


