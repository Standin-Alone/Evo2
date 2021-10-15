<?php

namespace App\Modules\DisbursementApproval\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DisbursementApprovalController extends Controller
{

    /**
     * Display the module welcome screen
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view("DisbursementApproval::index");
    }
}
