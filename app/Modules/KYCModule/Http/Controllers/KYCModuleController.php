<?php

namespace App\Modules\KYCModule\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Modules\KYCModule\Models\KYCModel;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\KYCImport;
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

        $file = request()->file('file');
        $kyc_import = new KYCImport();
        Excel::import($kyc_import, $file);

        return $kyc_import->getRowCount();
       
    }
}
