<?php

Route::resource('roles', 'RolesAndPermissionsController');
// Route::resource('/roles/sample', 'RolesAndPermissionsController');
Route::post('/roles/get-permissions', 'RolesAndPermissionsController@get_permissions')
            ->name('roles-get-permissions');

Route::post('/roles/get-module-permissions', 'RolesAndPermissionsController@get_module_permissions')
            ->name('roles-get-module-permissions');

Route::post('/roles/select-modules', 'RolesAndPermissionsController@select_modules')
            ->name('select-modules');

Route::post('/roles/set-permissions', 'RolesAndPermissionsController@set_permissions')
            ->name('roles-set-permissions');

Route::post('/roles/remove-module', 'RolesAndPermissionsController@remove_module')
            ->name('remove-module');