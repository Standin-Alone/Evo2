<?php

use Illuminate\Support\Facades\Route;

// Route::get('program-items-module', 'ProgramItemsModuleController@welcome');

Route::prefix('/program-items-module')->group(function () {
    /**
     * index
     */
    Route::get('/index', 'ProgramItemsModuleController@program_item_overview')->name('program_item_module.index');
    Route::get('/index/view-program-item-modal/{program_item_id?}', 'ProgramItemsModuleController@view_program_item')->name("program_item_module.view_selected_program_item");

    /**
     * Preview
     */

    /**
     *  Create
     */
    Route::get('/create_program_item/{reg_code?}', 'ProgramItemsModuleController@get_province')->name('program_item_module.region_code');
    Route::post('/create_program_item/preview/submit_form', 'ProgramItemsModuleController@create_program_items')->name('program_item_module.submit_create_program_item_form');
    // Route::post('/create_program_item/preview/submit_form', 'ProgramItemsModuleController@create_program_items')->name('program_item_module.submit_form');

    /**
     * Update
     */
    Route::get('/update_program_item/{reg_code?}', 'ProgramItemsModuleController@get_province')->name('program_item_module.update_region_code');
    Route::post('/update_program_item/submit_form', 'ProgramItemsModuleController@update_program_item')->name('program_item_module.submit_update_program_item_form');

    /**
     * Delete
     */
    Route::delete('/delete_program_item', 'ProgramItemsModuleController@delete_program_item')->name('program_item_module.submit_delete_program_item');
});
