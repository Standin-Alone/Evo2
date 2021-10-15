<?php
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\DownloadAppController;

Route::group([],function(){
    Route::resource('/DownloadApp','DownloadAppController');
    Route::get('/DownloadAppFiles','DownloadAppController@getDownloadAppFiles')->name('get.DownloadAppFiles');
});


