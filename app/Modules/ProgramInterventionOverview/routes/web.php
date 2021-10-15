<?php

//Route::get('program-intervention-overview', 'ProgramInterventionOverviewController@welcome');


//Route::group([],function(){
    Route::resource('/program-intervention-overview','ProgramInterventionOverviewController');
    Route::get('/get-programs', 'ProgramInterventionOverviewController@get_programs')->name('get-programs');
    Route::get('/get-program-liquidation', 'ProgramInterventionOverviewController@get_program_liquidation')->name('get-program-liquidation');
    Route::post('/get-program-payouts', 'ProgramInterventionOverviewController@get_program_payouts')->name('get-program-payouts');
    Route::post('/lock-intervention', 'ProgramInterventionOverviewController@lock_intervention')->name('lock-intervention');
//});