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
use App\Imports\ConsolidateKycImport;
use Illuminate\Support\Facades\Session;
use App\Events\ProgressEvent;


use App\Modules\KYCModule\Events\Progress;
use Event;

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

    public function uploading_index()
    {   $action = session('check_module_path');
        session()->put('progress',0);
        session()->save();     
   
       
        // $io->on('connection', function ($socket) use ($io) {
        //      $socket->broadcast->emit('progress','sample');
        //     $socket->on('progress', function ($data) use ($socket) {
           
        //     });
        // });
        $get_region = db::table('geo_map')->select(DB::raw('DISTINCT reg_code'),'reg_name')->get();
        return view("KYCModule::new-file-upload",compact('get_region','action'));
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
                                    DB::raw('date_uploaded'),
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
                                ->select(DB::raw('SUM(total_inserted) as total_inserted'),DB::raw('count(kyc_file_id) as total_files'),'date_uploaded')                        
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
                                        'total_rows','total_inserted','kf.date_uploaded as date_uploaded')
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
                            ->value('count_spti');

        $count_ussc =db::table('kyc_profiles as kp')
                            ->select(db::raw('count(kyc_id) as count_ussc'))
                            ->join('kyc_files as kf','kp.kyc_file_id','kf.kyc_file_id')
                            ->where('fintech_provider','UMSI')                                 
                            ->where(db::raw('DATE(kf.date_uploaded)'),db::raw('CURDATE()'))                                 
                            ->value('count_ussc');

         $count_files_today =db::table('kyc_files')          
                            ->select(db::raw('count(kyc_file_id) as count_files_stoday'))                               
                            ->where(db::raw('DATE(date_uploaded)'),db::raw('CURDATE()'))                                 
                            ->value('count_files_stoday');

        $count_records_today =db::table('kyc_profiles as kp')          
                            ->select(db::raw('count(kyc_id) as count_records_stoday'))  
                            ->join('kyc_files as kf','kp.kyc_file_id','kf.kyc_file_id')                             
                            ->where(db::raw('DATE(kf.date_uploaded)'),db::raw('CURDATE()'))                                 
                            ->value('count_records_stoday');


      return json_encode(["count_spti" => number_format((float)$count_spti), "count_ussc" =>  number_format((float)$count_ussc), "count_files_today" =>  number_format((float)$count_files_today), "count_records_today" =>  number_format((float)$count_records_today)]);
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
                            
                            ->value('count_spti');

        $count_ussc =db::table('kyc_profiles as kp')
                            ->select(db::raw('count(kyc_id) as count_ussc'))
                            ->join('kyc_files as kf','kp.kyc_file_id','kf.kyc_file_id')
                            ->where('fintech_provider','UMSI')                                                             
                            ->value('count_ussc');

         $count_files =db::table('kyc_files')          
                            ->select(db::raw('count(kyc_file_id) as count_files'))                               
                            ->where(db::raw('DATE(date_uploaded)'),db::raw('CURDATE()'))                                 
                            ->value('count_files');

        $count_records =db::table('kyc_profiles as kp')          
                            ->select(db::raw('count(kyc_id) as count_records'))  
                            ->join('kyc_files as kf','kp.kyc_file_id','kf.kyc_file_id')                                                         
                            ->value('count_records');


      return json_encode(["count_spti" => number_format((float)$count_spti), "count_ussc" =>  number_format((float)$count_ussc), "count_files" =>  number_format((float)$count_files), "count_records" =>  number_format((float)$count_records)]);
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

    // list of generated disbursements
    public function disbursement_generated_reports(){
        $get_records = db::table('dbp_batch as db')
                                ->select(DB::raw('DISTINCT name'),
                                            'gr.region',
                                            'db.created_at as generated_at',
                                            'total_amount',
                                            'created_by_fullname as generated_by',
                                            'approver_fullname as approved_by',
                                            'date_approved',
                                            'db.dbp_batch_id',
                                            DB::raw("(select COUNT(kyc_id) from kyc_profiles as kps where kps.dbp_batch_id = db.dbp_batch_id) as total_beneficiaries")                     
                                            )
                                ->join('kyc_profiles as kp','db.dbp_batch_id','kp.dbp_batch_id')                                
                                ->join('geo_region as gr','gr.code_reg','db.reg_code')                                                                
                                ->orderBy('db.created_at','desc')                                
                                ->get();
        return Datatables::of($get_records)->make(true);
    }

    // show more generated disbursement details
    public function disbursement_generated_show_more($dbp_batch_id){
        $PRIVATE_KEY =  '3273357538782F413F4428472B4B6250655368566D5971337436773979244226452948404D635166546A576E5A7234753778214125442A462D4A614E64526755'.
        '6A586E327235753778214125442A472D4B6150645367566B59703373367639792F423F4528482B4D6251655468576D5A7134743777217A25432646294A404E63'.
        '5166546A576E5A7234753777217A25432A462D4A614E645267556B58703273357638792F413F4428472B4B6250655368566D597133743677397A244326452948'.
        '2B4D6251655468576D5A7134743777397A24432646294A404E635266556A586E3272357538782F4125442A472D4B6150645367566B5970337336763979244226'.
        '4428472B4B6250655368566D5971337436773979244226452948404D635166546A576E5A7234753778214125432A462D4A614E645267556B5870327335763879';
        
        $get_records = db::table('kyc_profiles as kp')
                                ->select(
                                    'rsbsa_no',
                                    db::raw("CONCAT(first_name,' ',last_name) as full_name"),
                                    db::raw("CONCAT(IF(street_purok = '-' OR street_purok = '', '' , CONCAT(street_purok,', ')),'BRGY. ',barangay,', ',province,', ',region) as address"),
                                    'fintech_provider',
                                    'kyc_id',
                                    DB::raw("AES_DECRYPT(account_number,'".$PRIVATE_KEY."') as account_number"),
                                    DB::raw('date_uploaded'),
                                    'region'                                    
                                )  
                                ->where('dbp_batch_id',$dbp_batch_id)                                                                                                               
                                ->get();
        return Datatables::of($get_records)->make(true);
    }

    // upload file only to the server
    public function upload_file_only(){
        $file = request()->file('file');
        $provider  = '';
        $result  = '';
        $upload_path = 'temp_excel/kyc';
  

        $upload_folder  = $upload_path.'/'.Carbon::now()->year;

        if(!File::isDirectory($upload_path)){
            
            File::makeDirectory($upload_path, 0775, true);                                
            $by_year_path = $upload_path.'/'.Carbon::now()->year;
            if(!File::isDirectory($by_year_path)){

                File::makeDirectory($by_year_path, 0775, true);
            }
        }


 
        
        foreach($file as $key => $item_file){
         


            if(is_file($item_file)){
            $get_filename = $item_file->getClientOriginalName();
            $check_file_exist = db::table('ingest_files')->where('file_name',$get_filename)->take(1)->get();
            
            if($check_file_exist->isEmpty()){


                
                db::table('ingest_files')->insert(['file_name' => $get_filename ,'created_by_user_id' => session('user_id') ]);

                    
                // check file name if it has fintech provider
                if(str_contains($get_filename,'USSC') || str_contains($get_filename,'SPTI') ){

                    if(str_contains($get_filename,'USSC')){
                        $provider = 'UMSI';
                    }elseif(str_contains($get_filename,'SPTI') ){
                        $provider = 'SPTI';
                    }   
                
                
                Storage::disk('local')->put($upload_folder.'/'.$get_filename,file_get_contents($item_file));
                    
                
                    
                $result =  json_encode(["message" => 'true']);
                }else{
                    $result = json_encode(["message" => 'filename error']);
                }
            }else{

                foreach($check_file_exist as $val){
                    if($val->status == '0'){
                        $check_filename = db::table('kyc_files')->where('file_name',$get_filename)->whereColumn('total_inserted','total_rows')->first();

                        if($check_filename){
                            db::table('ingest_files')->insert(['file_name' => $get_filename ,'created_by_user_id' => session('user_id') ]);
                            Storage::disk('local')->put($upload_folder.'/'.$get_filename,file_get_contents($item_file));                            
                            $result = json_encode(["message" => 'true']);
                        }else{
                            $result = json_encode(["message" => 'Some files is already exist.']);
                        }
                        
                    }else{
                        Storage::disk('local')->put($upload_folder.'/'.$get_filename,file_get_contents($item_file));
                        $result = json_encode(["message" => 're-upload']);
                    }

                }
                
                
            }
        

            }
        }

        return $result;
    }

    // get list of files to ingest
    public function get_to_ingest_files(){
        $get_records = db::table('ingest_files as if')
                            ->select('if.file_name',DB::raw('IF(total_inserted is NULL,0,total_inserted) as total_inserted'),DB::raw('IF(total_rows is NULL,0,total_rows) as total_rows'),'if.date_created','ingest_file_id')
                            ->leftJoin('kyc_files as kf','kf.file_name','if.file_name')
                            ->where('created_by_user_id',session('user_id'))
                            ->where('if.status','1')                        
                            ->orderBy('if.date_created','DESC')                            
                            ->get();

        return Datatables::of($get_records)->make(true);                
    }


    // ingest file
    public function ingest_file(Request $request){
   
        
        

        Session::put(['progress' => 0]);
        Session::save(); 
        $file_name = request('file_name');
        $provider  = '';
        $result = '';
        $upload_path = 'temp_excel/kyc';
        
        $count_error = 0;

        $upload_folder  = $upload_path.'/'.Carbon::now()->year;

        if(!File::isDirectory($upload_path)){
            
            File::makeDirectory($upload_path, 0775, true);                                
            $by_year_path = $upload_path.'/'.Carbon::now()->year;
            if(!File::isDirectory($by_year_path)){

                File::makeDirectory($by_year_path, 0775, true);
            }
        }

        $error_storage = [];
    foreach($file_name as $item_filename){

        $count = '';

        $get_filename = $item_filename;

        
        $check_file = db::table('kyc_files')->where('file_name',$get_filename)->whereColumn('total_inserted','total_rows')->take(1)->get();

        // check file if the file is already uploaded
        // if(!$check_file){
            // check file name if it has fintech provider
            if(str_contains($get_filename,'USSC') || str_contains($get_filename,'SPTI') ){

                if(str_contains($get_filename,'USSC')){
                    $provider = 'UMSI';
                }elseif(str_contains($get_filename,'SPTI') ){
                    $provider = 'SPTI';
                }
    
                $kyc_import = new KYCImport($provider,$get_filename);
                
                                                    
                Excel::import($kyc_import,$upload_folder.'/'.$get_filename);
                $import_file = $kyc_import->newResult();
                
                if($import_file){
                    
                    // check if import file has errors
                    if(count($import_file['error_data']) == 0){

                        db::table('ingest_files')
                            ->where('file_name',$get_filename)
                            ->update(['status'=>'0']);
                            
                        
                    }else{

                        if(count($error_storage) > 0){
                            $error_storage = array_merge($error_storage[0], $import_file['error_data']);                        

                        }else{
                            array_push($error_storage, $import_file['error_data'] );                        
                        }  
                    }


              
                }                        
            }else{
                $count_error++;
                echo  json_encode(["message" => 'filename error']);
                // $response = \Response::make(["message" => 'filename error']);
                
            }
        // }
    }

    if($count_error == 0){
        echo  json_encode(["message" => 'true','error_data' => $error_storage]);
        // $response = \Response::make(["message" => 'true','error_data' => $error_storage]);
    
    }else{
        echo  json_encode(["message" => 'false']);
        // $response = \Response::make(["message" => 'false']);
    }
    
 
    // $response->header('Content-Type', 'application/json');
    // return $response;

    }




}
