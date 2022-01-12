<?php
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\SupplierMainBranchController;

Route::group([],function(){
    Route::resource('/SupplierMainBranch','SupplierMainBranchController');  
    Route::get('/SupplierMainBranchList', 'SupplierMainBranchController@Viewing_MainBranch')->name('Viewing.MainBranch');
    Route::get('/SupplierSubMainBranchList', 'SupplierMainBranchController@Viewing_SubMainBranch')->name('Viewing.SubMainBranch');

    Route::post('/SupplierMainBranchInsert', 'SupplierMainBranchController@Inserting_MainBranch')->name('Inserting.MainBranch');
    Route::post('/SupplierMainBranchUpdate', 'SupplierMainBranchController@Updating_MainBranch')->name('Updating.MainBranch');
    Route::post('/SupplierMainBranchRemove', 'SupplierMainBranchController@Removing_MainBranch')->name('Removing.MainBranch');
});