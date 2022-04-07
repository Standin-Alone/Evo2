<?php

Route::group([],function(){
    Route::resource('modules','ModulesController');
    Route::post('/modules/store-sub-modules','ModulesController@store_sub_modules')->name('modules.store_sub_modules')->middleware('ensure.user.has.role');
    Route::get('/modules/show-sub-modules/{parent_module_id}','ModulesController@show_sub_modules')->name('modules.show_sub_modules');
    
});



