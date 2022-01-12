<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RegistrationController;

Route::group([],function(){
    Route::resource('/Registration','RegistrationController');

    Route::get('/uservalidation', 'RegistrationController@validateid');
    Route::get('/getRegion', 'RegistrationController@getRegion');
    Route::post('/findprovince', 'RegistrationController@findProvince')->name('fetch.province');
    Route::post('/findmunicipality', 'RegistrationController@findMunicipality')->name('fetch.municipality');
    Route::post('/findbarangay', 'RegistrationController@findBarangay')->name('fetch.barangay');
    Route::post('/saveregistration', 'RegistrationController@saveregistration')->name('save.registration');
    Route::post('/getregsstatus', 'RegistrationController@getregsstatus')->name('get.regs-status');

    // http://localhost/e_voucher/uservalidation?id=0a7a1226-a6c7-4b57-94d0-668e2293b458
});


