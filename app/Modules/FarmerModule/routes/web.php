<?php
use Illuminate\Support\Facades\Route;

Route::prefix('/farmer')->group(function () {
    Route::get('/index', 'FarmerModuleController@index')->name("farmer.index");
    // Route::get('/index', 'FarmerModuleController@list_of_farmers_info_view')->name("farmer.index");

    // This route is for viewing the farmer details for the new page. 
    Route::get('/view-details/{ref_no}','FarmerModuleController@view_farmer_details_page')->name("farmer.view.details.page");
});
