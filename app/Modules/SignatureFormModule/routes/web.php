<?php

use Illuminate\Support\Facades\Route;

Route::get('signature-form-module', 'SignatureFormModuleController@welcome');


Route::get('/sign-form', 'SignatureFormModuleController@generate_signature_form_spti')->name('generate-form');
