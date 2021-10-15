<?php

//Route::group([],function(){
    Route::resource('/create-program-intervention','ProgramInterventionController');
    Route::post('/create-intervention', 'ProgramInterventionController@create_intervention')->name('create-intervention');
//});