<?php
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\DisbursementApprovalController;

Route::group([],function(){
    Route::resource('/DisbursementApproval','DisbursementApprovalController');
    
   });


