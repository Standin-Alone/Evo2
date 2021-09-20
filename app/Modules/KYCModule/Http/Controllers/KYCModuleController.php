<?php

namespace App\Modules\KYCModule\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Modules\KYCModule\Models\KYCModel;
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

    public function import(){
        $kyc_model = new KYCModel();
        $kyc_model->
    }
}
