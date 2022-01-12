<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SubmittedDisbursementListController;

Route::group([],function(){
    Route::resource('/SubmittedDisbursementList','SubmittedDisbursementListController');
    Route::get('/getDisbursementList','SubmittedDisbursementListController@getSubmittedDisbursementList')->name('get.SubmittedDisbursementList'); 
    Route::post('/postDisbursementfile','SubmittedDisbursementListController@postDisbursementfile')->name('post.Disbursementfile'); 
    
});


