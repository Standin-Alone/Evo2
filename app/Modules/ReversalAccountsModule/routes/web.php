<?php
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\ReversalAccountsModuleController;

Route::group([],function(){
    Route::resource('/ReversalAccountsModule','ReversalAccountsModuleController');
    Route::get('/ReversalAccountsModuleList','ReversalAccountsModuleController@getReversalAccountsModuleList')->name('get.ReversalAccountsModuleList');
    Route::get('/ReversalAccountsModuleDetails','ReversalAccountsModuleController@getReversalAccountsModuleDetails')->name('get.ReversalAccountsModuleDetails');
    Route::get('/GeneratedReversalAccountsModule','ReversalAccountsModuleController@getGeneratedReversalAccountsModule')->name('get.GeneratedReversalAccountsModule');
    Route::get('/DownloadReversalAccountsModuleTexfile','ReversalAccountsModuleController@downloadReversalAccountsModuleTexfile')->name('download.ReversalAccountsModuleTexfile');
    Route::get('/GeneratedFileDetails','ReversalAccountsModuleController@getGeneratedFileDetails')->name('get.GeneratedFileDetails');
    Route::post('/previewSeletedBeneficiaries','ReversalAccountsModuleController@getpreviewSeletedBeneficiaries')->name('get.previewSeletedBeneficiaries');
    Route::post('/ReturnGeneratedTextfile','ReversalAccountsModuleController@ReturnGeneratedTextfile')->name('Return.GeneratedTextfile');

    Route::post('/SubmitReversalAccountsModuleDetails','ReversalAccountsModuleController@submitReversalAccountsModuleDetails')->name('submit.ReversalAccountsModuleDetails');
});


