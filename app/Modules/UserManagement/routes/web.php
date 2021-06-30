<?php

// Route::resource('user-management', 'UserManagementController');


Route::group([],function(){
    Route::resource('/user','UserManagementController');
});



