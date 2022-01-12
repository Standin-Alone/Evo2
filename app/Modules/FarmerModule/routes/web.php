<?php
use Illuminate\Support\Facades\Route;

Route::prefix('/farmer')->group(function () {
    Route::get('/index', 'FarmerModuleController@index')->name("farmer.index");
    Route::get('/index/rffa', 'FarmerModuleController@rffa_farmer_list_view')->name("farmer.index.rffa");
    // This route is for viewing the farmer details for the new page. 
    Route::get('/view-details/{program_id?}/{ref_no?}','FarmerModuleController@view_farmer_details_page')->name("farmer.view.details.page");
    Route::get('/view-details/rffa/{program_id?}/{dbp_batch_id?}/{rsbsa_no?}','RffaFarmerController@view_rffa_farmer_details_page')->name("farmer.view.rffa_details.page");
});
