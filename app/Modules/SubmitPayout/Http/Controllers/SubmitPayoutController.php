<?php

namespace App\Modules\SubmitPayout\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class SubmitPayoutController extends Controller
{

    /**
     * Display the module welcome screen
     *
     * @return \Illuminate\Http\Response
     */
    public function welcome()
    {
        return view("SubmitPayout::welcome");
    }
}
