<?php

namespace App\Modules\SampleModule\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class SampleModuleController extends Controller
{

    /**
     * Display the module welcome screen
     *
     * @return \Illuminate\Http\Response
     */
    public function welcome()
    {
        return view("SampleModule::welcome");
    }
}
