<?php

namespace App\Modules\DownloadMobileApp\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DownloadMobileAppController extends Controller
{

    /**
     * Display the module welcome screen
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view("DownloadMobileApp::welcome");
    }
}
