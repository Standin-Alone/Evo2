<?php

Route::group([],function(){
    Route::resource('modules','ModulesController');
    Route::get('/modules/show-sub-modules/{parent_module_id}','ModulesController@show_sub_modules')->name('modules.show_sub_modules');
    
});



