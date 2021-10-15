<?php
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\PayoutSupervisorApprovalController;

Route::group([],function(){
    Route::resource('/PayoutSupervisorApproval','PayoutSupervisorApprovalController');
    
   });


