<?php

// Route::resource('user-management', 'UserManagementController');


Route::group([],function(){
    Route::get('/user','UserManagementController@index');
    Route::post('/user/add','UserManagementController@store')->name('user-add');

    Route::get('/user/check-email','UserManagementController@checkEmail')->name('check-email');
    Route::get('/user/filter-role/{agency_loc}','UserManagementController@filter_role')->name('filter-role');
    Route::get('/user/filter-province/{region_code}','UserManagementController@filter_province')->name('filter-province');
    Route::get('/user/filter-municipality/{province_code}','UserManagementController@filter_municipality')->name('filter-municipality');
    Route::get('/user/filter-barangay/{municipality_code}','UserManagementController@filter_barangay')->name('filter-barangay');
});



