<?php
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\SubmitPayoutFilesController;

Route::group([],function(){
    Route::resource('/SubmitPayoutFiles','SubmitPayoutFilesController');
    Route::get('/SubmitPayoutFileList','SubmitPayoutFilesController@getSubmitPayoutFileList')->name('get.SubmitPayoutFileList');
    Route::get('/GeneratePrivateKey','SubmitPayoutFilesController@GeneratePrivateKey')->name('generate.PrivateKey');
    Route::get('/GeneratedTextfileHistory','SubmitPayoutFilesController@getGeneratedTextfileHistory')->name('get.GeneratedTextfileHistory');
});


