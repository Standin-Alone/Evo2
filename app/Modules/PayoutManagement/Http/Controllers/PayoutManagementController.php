<?php

namespace App\Modules\PayoutManagement\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class PayoutManagementController extends Controller
{

    /**
     * Display the module welcome screen
     *
     * @return \Illuminate\Http\Response
     */
    public function welcome()
    {
        return view("PayoutManagement::welcome");
    }
}
