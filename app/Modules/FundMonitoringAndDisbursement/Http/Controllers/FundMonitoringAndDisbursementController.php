<?php

namespace App\Modules\FundMonitoringAndDisbursement\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class FundMonitoringAndDisbursementController extends Controller
{

    /**
     * Display the module welcome screen
     *
     * @return \Illuminate\Http\Response
     */
    public function welcome()
    {
        return view("FundMonitoringAndDisbursement::welcome");
    }
}
