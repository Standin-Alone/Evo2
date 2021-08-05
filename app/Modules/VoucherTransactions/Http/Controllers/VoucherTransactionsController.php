<?php

namespace App\Modules\VoucherTransactions\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class VoucherTransactionsController extends Controller
{

    /**
     * Display the module welcome screen
     *
     * @return \Illuminate\Http\Response
     */
    public function welcome()
    {
        return view("VoucherTransactions::welcome");
    }
}
