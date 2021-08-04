<?php

namespace App\Modules\VoucherTransaction\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class VoucherTransactionController extends Controller
{

    /**
     * Display the module welcome screen
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view("VoucherTransaction::welcome");
    }
}
