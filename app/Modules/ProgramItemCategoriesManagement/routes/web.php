<?php

use Illuminate\Support\Facades\Route;


Route::group(["prefix" => '/program-item-categories-management'],function(){
    Route::get('/','ProgramItemCategoriesManagementController@index')->name('pim-index');
    
    Route::get('/show','ProgramItemCategoriesManagementController@show')->name('pim-show');
    Route::get('/show-registered-program-sub-category/{fertilizer_category_id?}','ProgramItemCategoriesManagementController@show_registered_program_sub_category')->name('show-registered-program-sub-category');    
    Route::get('/filter-program-for-sub-category/{fertilizer_sub_category_id?}','ProgramItemCategoriesManagementController@filter_program_for_sub_category')->name('filter-program-for-sub-category');
    Route::get('/filter-program-for-category/{fertilizer_category_id?}','ProgramItemCategoriesManagementController@filter_program_for_category')->name('filter-program-for-category');
    Route::post('/add-category','ProgramItemCategoriesManagementController@add_category')->name('add-category');
    Route::post('/set-program-sub-category','ProgramItemCategoriesManagementController@set_program_sub_category')->name('set-program-sub-category');
    Route::post('/set-program-category','ProgramItemCategoriesManagementController@set_program_category')->name('set-program-category');
    Route::post('/remove-program-sub-category','ProgramItemCategoriesManagementController@remove_program_sub_category')->name('remove-program-sub-category');
    Route::post('/remove-program-category','ProgramItemCategoriesManagementController@remove_program_category')->name('remove-program-category');
});