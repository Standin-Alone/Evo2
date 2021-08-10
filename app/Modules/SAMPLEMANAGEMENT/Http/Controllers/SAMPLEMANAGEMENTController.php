<?php

namespace App\Modules\SAMPLEMANAGEMENT\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SAMPLEMANAGEMENTController extends Controller
{

    /**
     * Display the module welcome screen
     *
     * @return \Illuminate\Http\Response
     */
    public function welcome()
    {
        return view("SAMPLEMANAGEMENT::welcome");
    }
}
