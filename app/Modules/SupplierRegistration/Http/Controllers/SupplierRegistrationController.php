<?php

namespace App\Modules\SupplierRegistration\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SupplierRegistration;
class SupplierRegistrationController extends Controller
{

    /**
     * Display the module welcome screen
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view("SupplierRegistration::index");
    }

    public function store(){


    }

    
}
