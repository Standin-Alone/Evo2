<?php

namespace App\Modules\DownloadMobileApplications\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class DownloadMobileApplicationsController extends Controller
{

    /**
     * Display the module welcome screen
     *
     * @return \Illuminate\Http\Response
     */
    public function welcome()
    {
        return view("DownloadMobileApplications::welcome");
    }
}
