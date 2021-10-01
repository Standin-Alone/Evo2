<?php

namespace App\Modules\PayoutMonitoring\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class PayoutMonitoringController extends Controller
{

    /**
     * Display the module welcome screen
     *
     * @return \Illuminate\Http\Response
     */
    public function welcome()
    {
        return view("PayoutMonitoring::welcome");
    }
}
