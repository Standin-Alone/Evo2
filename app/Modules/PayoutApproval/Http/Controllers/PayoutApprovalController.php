<?php

namespace App\Modules\PayoutApproval\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class PayoutApprovalController extends Controller
{

    /**
     * Display the module welcome screen
     *
     * @return \Illuminate\Http\Response
     */
    public function welcome()
    {
        return view("PayoutApproval::welcome");
    }
}
