<?php

namespace App\Modules\DownloadMobileApplication\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class DownloadMobileApplicationController extends Controller
{

    /**
     * Display the module welcome screen
     *
     * @return \Illuminate\Http\Response
     */
    public function welcome()
    {
        return view("DownloadMobileApplication::welcome");
    }
}
