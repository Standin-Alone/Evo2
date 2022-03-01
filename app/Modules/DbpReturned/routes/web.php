<?php

use Illuminate\Support\Facades\Route;

Route::get('dbp-returned', 'DbpReturnedController@welcome');

Route::prefix('/dbp-returned')->group(function () {

    Route::get('/list-of-return-disbursement_files', 'DbpReturnedController@main_index')->name('dbp-returned-module.index');


});
