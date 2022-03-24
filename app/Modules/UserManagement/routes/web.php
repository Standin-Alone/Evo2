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
    Route::get('/user/list-of-users/{uuid}/user-status','UserManagementController@show_user_otp_status')->name('list-of-users.user-status');
    Route::get('/user/list-of-users/check-program-permission/{uuid}', 'UserManagementController@check_program_permission')->name('list-of-users.check_program_permission');
    Route::post('/user/add-new-user-role', 'UserManagementController@add_user_role')->name('list-of-users.add_user_role');

    Route::post('/user/send-account-creation-link', 'AccountCreationController@send_account_creation_link')->name('send-account-creation-link');
    Route::get('/user/account-creation/{user_id}', 'AccountCreationController@account_creation_index')->name('account-creation-index');

    Route::get('/account-creation/filter-role/{agency_loc}','AccountCreationController@filter_role')->name('ac-filter-role');
    Route::get('/account-creation/filter-province/{region_code}','AccountCreationController@filter_province')->name('ac-filter-province');
    Route::get('/account-creation/filter-municipality/{province_code}','AccountCreationController@filter_municipality')->name('ac-filter-municipality');
    Route::get('/account-creation/filter-barangay/{region_code}/{province_code}/{municipality_code}','AccountCreationController@filter_barangay')->name('ac-filter-barangay');
    Route::post('/account-creation/add','AccountCreationController@store')->name('create-account');
});



