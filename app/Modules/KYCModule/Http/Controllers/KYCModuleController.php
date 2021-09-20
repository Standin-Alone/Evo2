<?php

namespace App\Modules\KYCModule\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class KYCModuleController extends Controller
{

    /**
     * Display the module welcome screen
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view("KYCModule::index");
    }
}
