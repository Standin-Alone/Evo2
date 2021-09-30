<?php

namespace App\Modules\KYCModule\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Modules\KYCModule\Models\KYCModel;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\KYCImport;
use DB;
class KYCModuleController extends Controller
{

    public function __constructor(){
        $this->middleware('session.module');
    }
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
        $provider = request('provider');
        $kyc_import = new KYCImport($provider);
        Excel::import($kyc_import, $file);

        return $kyc_import->getRowCount();
       
    }

    public function show(){
        


        $get_records = db::table('kyc_profiles')
                                ->select(
                                    'rsbsa_no',
                                    db::raw("CONCAT(first_name,' ',last_name) as full_name"),
                                    db::raw("CONCAT(province,', ',region) as address"),
                                    'fintech_provider',
                                    'kyc_id'                                 
                                )->get();

        return datatables($get_records)->toJson();
    }
}
