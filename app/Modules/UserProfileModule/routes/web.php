<?php

use Illuminate\Support\Facades\Route;

Route::get('/user/profile', 'UserProfileModuleController@index')->name('user.profile');
Route::patch('/user/profile/information', 'UserProfileModuleController@user_information')->name('user.profile_info');
Route::patch('/user/profile/password', 'UserProfileModuleController@password')->name('user.profile_password');