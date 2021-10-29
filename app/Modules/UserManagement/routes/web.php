<?php

// Route::resource('user-management', 'UserManagementController');


Route::group([],function(){
    Route::get('/user','UserManagementController@index')->name('user.index');
    Route::get('/user/show','UserManagementController@show')->name('user.show');
    Route::get('/user/destroy/{id}','UserManagementController@destroy')->name('user.destroy');
    Route::get('/user/block/{id}','UserManagementController@block')->name('user.block');
    Route::get('/user-email','UserManagementController@email');

    Route::post('/user/add','UserManagementController@store')->name('user-add');
    Route::post('/user/update','UserManagementController@update')->name('user-update');
    Route::post('/user/import-file','UserManagementController@import_file')->name('import-file');

    Route::get('/user/check-email','UserManagementController@checkEmail')->name('check-email');
    Route::get('/user/filter-role/{agency_loc}','UserManagementController@filter_role')->name('filter-role');
    Route::get('/user/filter-province/{region_code}','UserManagementController@filter_province')->name('filter-province');
    Route::get('/user/filter-municipality/{province_code}','UserManagementController@filter_municipality')->name('filter-municipality');
    Route::get('/user/filter-barangay/{region_code}/{province_code}/{municipality_code}','UserManagementController@filter_barangay')->name('filter-barangay');

    Route::get('/user/list-of-users','UserManagementController@list_of_users')->name('list-of-users.index');
    Route::get('/user/list-of-users/{uuid}','UserManagementController@user_details')->name('list-of-users.user-details');
    Route::post('/user/add-new-user-role', 'UserManagementController@add_user_role')->name('list-of-users.add_user_role');
});




