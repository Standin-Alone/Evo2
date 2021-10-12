<?php

namespace App\Modules\KYCModule\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Modules\KYCModule\Models\KYCModel;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\KYCImport;
use DB;
use Yajra\DataTables\Facades\DataTables;
use Carbon\Carbon;
use File;
use Mail;
use Illuminate\Support\Facades\Storage;
use App\Models\HomeModel;
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
        $role = '';
        $region = '';
        $total_inserted_count = 0;
      
        $upload_path = 'temp_excel/kyc';
  

        $upload_folder  = $upload_path.'/'.Carbon::now()->year;

        if(!File::isDirectory($upload_path)){
            
            File::makeDirectory($upload_path, 0775, true);                                
            $by_year_path = $upload_path.'/'.Carbon::now()->year;
            if(!File::isDirectory($by_year_path)){

                File::makeDirectory($by_year_path, 0775, true);
            }
        }


        $get_filename = $file->getClientOriginalName();

        
        Storage::disk('local')->put($upload_folder.'/'.$get_filename,file_get_contents($file));
            

        $kyc_import = new KYCImport($provider);
        Excel::import($kyc_import, $file);

        
        return $kyc_import->getRowCount();       
    }

    public function show(){
        
        DB::connection()->disableQueryLog();

        $PRIVATE_KEY =  '3273357538782F413F4428472B4B6250655368566D5971337436773979244226452948404D635166546A576E5A7234753778214125442A462D4A614E64526755'.
                        '6A586E327235753778214125442A472D4B6150645367566B59703373367639792F423F4528482B4D6251655468576D5A7134743777217A25432646294A404E63'.
                        '5166546A576E5A7234753777217A25432A462D4A614E645267556B58703273357638792F413F4428472B4B6250655368566D597133743677397A244326452948'.
                        '2B4D6251655468576D5A7134743777397A24432646294A404E635266556A586E3272357538782F4125442A472D4B6150645367566B5970337336763979244226'.
                        '4428472B4B6250655368566D5971337436773979244226452948404D635166546A576E5A7234753778214125432A462D4A614E645267556B5870327335763879';

        $get_records = db::table('kyc_profiles')
                                ->select(
                                    'rsbsa_no',
                                    db::raw("CONCAT(first_name,' ',last_name) as full_name"),
                                    db::raw("CONCAT(IF(street_purok = '-' OR street_purok = '', '' , CONCAT(street_purok,', ')),'BRGY. ',barangay,', ',province,', ',region) as address"),
                                    'fintech_provider',
                                    'kyc_id',
                                    DB::raw("AES_DECRYPT(account_number,'".$PRIVATE_KEY."') as account_number"),
                                    'date_uploaded'                          
                                )
                                ->where('uploaded_by_user_id',session('uuid'))
                                ->orderBy('date_uploaded','DESC')
                                ->get();

        // return datatables($get_records)->toJson();
        return Datatables::of($get_records)->make(true);
        
    }
}
