<?php

// Route::resource('user-management', 'UserManagementController');


Route::group(['prefix'=>'/mobile-version-control'],function(){
    Route::get('/','MobileVersionControlController@index')->name('mobile-version-control.index');
    Route::post('/add-mobile-apk','MobileVersionControlController@add_apk')->name('add-apk');   
    Route::get('/show','MobileVersionControlController@show')->name('mobile-version-control-show');   
});



