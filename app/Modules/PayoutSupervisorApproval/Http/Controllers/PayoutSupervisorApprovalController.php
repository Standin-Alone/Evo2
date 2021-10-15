<?php

namespace App\Modules\PayoutSupervisorApproval\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PayoutSupervisorApprovalController extends Controller
{

    /**
     * Display the module welcome screen
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view("PayoutSupervisorApproval::index");
    }
}
