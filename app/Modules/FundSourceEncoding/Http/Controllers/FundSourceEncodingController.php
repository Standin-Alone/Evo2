<?php

namespace App\Modules\FundSourceEncoding\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class FundSourceEncodingController extends Controller
{

    /**
     * Display the module welcome screen
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view("FundSourceEncoding::welcome");
    }
}
