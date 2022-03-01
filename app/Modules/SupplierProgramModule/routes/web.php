<?php

use Illuminate\Support\Facades\Route;

// Route::get('supplier-program-module', 'SupplierProgramModuleController@welcome');

Route::prefix('/supplier-program-module')->group(function () {
    /**
     * Index
     */
    Route::get('/index', 'SupplierProgramModuleController@list_of_suppliers')->name('supplier_program_module.list_of_suppliers');

    Route::get('/index/list-of-supplier-programs', 'SupplierProgramModuleController@show_list_of_supplier_programs')->name('supplier_program_module.list_of_supplier_programs');

    /**
     * Setup program and program items view
     */
    Route::post('/create-setup-program-and-program-items/preview', 'SupplierProgramModuleController@preview_setup_program')->name('supplier_program_module.preview_form');

    /**
     * Create setup program and program items 
     */
    Route::post('/create-setup-program-and-program-items/preview/datas', 'SupplierProgramModuleController@get_setup_program_and_program_items')->name('supplier_program_module.get_program_id_and_program_item_id');

    Route::post('/create-setup-program-and-program-item/preview/submit-form', 'SupplierProgramModuleController@add_setup_program_and_program_item')->name('supplier_program_module.submit_created_setup');
});
