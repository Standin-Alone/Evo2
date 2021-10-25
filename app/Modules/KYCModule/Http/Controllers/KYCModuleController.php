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
use Illuminate\Support\Facades\Storage;
class KYCModuleController extends Controller
{

    public function __construct(){
        $this->middleware('session.module');
    }
    /**
     * Display the module welcome screen
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {   $action = session('check_module_path');
 
        $get_region = db::table('geo_map')->select(DB::raw('DISTINCT reg_code'),'reg_name')->get();
        return view("KYCModule::index",compact('get_region','action'));
    }

    // import function of kyc profiles
    public function import(){        

        $file = request()->file('file');
        $provider  = '';
      
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
        
        

        // check file name if it has fintech provider
        if(str_contains($get_filename,'USSC') || str_contains($get_filename,'SPTI') ){

            if(str_contains($get_filename,'USSC')){
                $provider = 'UMSI';
            }elseif(str_contains($get_filename,'SPTI') ){
                $provider = 'SPTI';
            }

            $kyc_import = new KYCImport($provider,$get_filename);
            
            Storage::disk('local')->put($upload_folder.'/'.$get_filename,file_get_contents($file));
            
        
            Excel::import($kyc_import, $file);
            return $kyc_import->getRowCount();
        }else{
            return json_encode(["message" => 'filename error']);
        }
        
        

        
       
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
                                    DB::raw('DATE_FORMAT(date_uploaded, "%M %d, %Y") as date_uploaded'),
                                    'region'                      
                                )                                
                                ->orderBy('date_uploaded','DESC')
                                ->get();

        // return datatables($get_records)->toJson();
        return Datatables::of($get_records)->make(true);
        
    }

    // summary files report
    public function summary_files_report(){
        $get_records = db::table('kyc_files')
                                ->select(DB::raw('SUM(total_inserted) as total_inserted'),DB::raw('count(kyc_file_id) as total_files'),DB::raw('DATE_FORMAT(date_uploaded, "%M %d, %Y") as date_uploaded'))                        
                                ->groupBy(DB::raw('DATE(date_uploaded)'))    
                                ->orderBy('date_uploaded','DESC')                            
                                ->get();

        return Datatables::of($get_records)->make(true);
    }

    // file reports
    public function file_data_reports(){

            $get_records = db::table('kyc_profiles as kp')
                                ->select('region',
                                        'province',
                                        'file_name',
                                        DB::raw("IF(fintech_provider = 'UMSI','USSC',fintech_provider) as fintech_provider"),
                                        'total_rows','total_inserted', DB::raw('DATE_FORMAT(kf.date_uploaded, "%M %d, %Y") as date_uploaded'))
                                ->join('kyc_files as kf','kp.kyc_file_id','kf.kyc_file_id')
                                ->groupBy('region','province','file_name')    
                                ->orderBy('kf.date_uploaded','DESC')                            
                                ->get();

          return Datatables::of($get_records)->make(true);

    }

    // kyc card summary TODAY
    public function kyc_card_summary_today(){

        $summary = [];

        $count_spti =db::table('kyc_profiles as kp')
                            ->select(db::raw('count(kyc_id) as count_spti'))
                            ->join('kyc_files as kf','kp.kyc_file_id','kf.kyc_file_id')
                            ->where('fintech_provider','SPTI')                                 
                            ->where(db::raw('DATE(kf.date_uploaded)'),db::raw('CURDATE()'))                                 
                            ->pluck('count_spti');

        $count_ussc =db::table('kyc_profiles as kp')
                            ->select(db::raw('count(kyc_id) as count_ussc'))
                            ->join('kyc_files as kf','kp.kyc_file_id','kf.kyc_file_id')
                            ->where('fintech_provider','UMSI')                                 
                            ->where(db::raw('DATE(kf.date_uploaded)'),db::raw('CURDATE()'))                                 
                            ->pluck('count_ussc');

         $count_files_today =db::table('kyc_files')          
                            ->select(db::raw('count(kyc_file_id) as count_files_stoday'))                               
                            ->where(db::raw('DATE(date_uploaded)'),db::raw('CURDATE()'))                                 
                            ->pluck('count_files_stoday');

        $count_records_today =db::table('kyc_profiles as kp')          
                            ->select(db::raw('count(kyc_id) as count_records_stoday'))  
                            ->join('kyc_files as kf','kp.kyc_file_id','kf.kyc_file_id')                             
                            ->where(db::raw('DATE(kf.date_uploaded)'),db::raw('CURDATE()'))                                 
                            ->pluck('count_records_stoday');


      return json_encode(["count_spti" => $count_spti, "count_ussc" =>  $count_ussc, "count_files_today" =>  $count_files_today, "count_records_today" =>  $count_records_today]);
    }   


    // Report View Start here
    public function report_index(){    
        $action = session('check_module_path'); 
        $get_region = db::table('geo_map')->select(DB::raw('DISTINCT reg_code'),'reg_name')->get();
        return view("KYCModule::reports.kyc-report",compact('get_region','action'));
    }



    // kyc card summary
    public function kyc_card_summary_all(){

        $summary = [];

        $count_spti =db::table('kyc_profiles as kp')
                            ->select(db::raw('count(kyc_id) as count_spti'))
                            ->join('kyc_files as kf','kp.kyc_file_id','kf.kyc_file_id')
                            ->where('fintech_provider','SPTI')                                 
                            
                            ->pluck('count_spti');

        $count_ussc =db::table('kyc_profiles as kp')
                            ->select(db::raw('count(kyc_id) as count_ussc'))
                            ->join('kyc_files as kf','kp.kyc_file_id','kf.kyc_file_id')
                            ->where('fintech_provider','UMSI')                                                             
                            ->pluck('count_ussc');

         $count_files =db::table('kyc_files')          
                            ->select(db::raw('count(kyc_file_id) as count_files'))                               
                            ->where(db::raw('DATE(date_uploaded)'),db::raw('CURDATE()'))                                 
                            ->pluck('count_files');

        $count_records =db::table('kyc_profiles as kp')          
                            ->select(db::raw('count(kyc_id) as count_records'))  
                            ->join('kyc_files as kf','kp.kyc_file_id','kf.kyc_file_id')                                                         
                            ->pluck('count_records');


      return json_encode(["count_spti" => $count_spti, "count_ussc" =>  $count_ussc, "count_files" =>  $count_files, "count_records" =>  $count_records]);
    }   


    public function region_fintech_reports(){
        $get_records = db::table('kyc_profiles as kp')
                                ->select('region',
                                        DB::raw("IF(fintech_provider = 'UMSI','USSC',fintech_provider) as fintech_provider"),
                                        DB::raw('count(kyc_id) as total_records_uploaded')
                                        
                                    )
                                ->join('kyc_files as kf','kp.kyc_file_id','kf.kyc_file_id')
                                ->groupBy('region','fintech_provider')    
                                ->orderBy('kf.date_uploaded','DESC')                            
                                ->get();
            return Datatables::of($get_records)->make(true);
    }
}
