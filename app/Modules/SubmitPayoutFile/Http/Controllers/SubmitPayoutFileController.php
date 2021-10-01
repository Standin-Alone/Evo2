<?php

namespace App\Modules\SubmitPayoutFile\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class SubmitPayoutFileController extends Controller
{

    /**
     * Display the module welcome screen
     *
     * @return \Illuminate\Http\Response
     */
    public function welcome()
    {
        return view("SubmitPayoutFile::welcome");
    }
}
