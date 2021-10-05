<?php

namespace App\Modules\KYCModule\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Modules\KYCModule\Models\KYCModel;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\KYCImport;
use DB;
use Yajra\DataTables\Facades\DataTables;
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
        
        DB::connection()->disableQueryLog();

        $get_records = db::table('kyc_profiles')
                                ->select(
                                    'rsbsa_no',
                                    db::raw("CONCAT(first_name,' ',last_name) as full_name"),
                                    db::raw("CONCAT(province,', ',region) as address"),
                                    'fintech_provider',
                                    'kyc_id'                                 
                                )
                                ->where('uploaded_by_user_id',session('uuid'))
                                ->orderBy('date_uploaded','DESC')
                                ->get();

        // return datatables($get_records)->toJson();
        return Datatables::of($get_records)->make(true);
        
    }
}
