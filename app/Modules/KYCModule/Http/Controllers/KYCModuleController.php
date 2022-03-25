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
use Illuminate\Support\Facades\Session;

use ElephantIO\Client;
use ElephantIO\Engine\SocketIO\Version2X;


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
       
        $get_region = db::table('geo_map')->select(DB::raw('DISTINCT reg_code'),'reg_name')->get();
        $get_agency = db::table('agency')->get();
        return view("KYCModule::new-file-upload",compact('get_region','action','get_agency'));
    }

    // render view of other kyc report
    public function kyc_profiles_report_index(){
        $action = session('check_module_path');
 
        $get_region = db::table('geo_map')->select(DB::raw('DISTINCT reg_code'),'reg_name')->get();
        return view("KYCModule::reports.other-kyc-report",compact('get_region','action'));
    }

    

    public function show(){
        
        DB::connection()->disableQueryLog();



        $get_records = db::table('kyc_profiles as kp')
                                ->select(
                                    'rsbsa_no',
                                    db::raw("CONCAT(first_name,' ',last_name) as full_name"),
                                    db::raw("CONCAT(IF(street_purok = '-' OR street_purok = '', '' , CONCAT(street_purok,', ')),'BRGY. ',barangay,', ',province,', ',region) as address"),
                                    'fintech_provider',
                                    'kyc_id',
                                    'account_number',                                    
                                    DB::raw('date_uploaded'),
                                    'region'                      
                                )                                
                                ->where('kp.program_id',session('Default_Program_Id'))
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
                                ->where('kp.program_id',session('Default_Program_Id'))
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
                            ->where('kp.program_id',session('Default_Program_Id'))                             
                            ->value('count_spti');

        $count_ussc =db::table('kyc_profiles as kp')
                            ->select(db::raw('count(kyc_id) as count_ussc'))
                            ->join('kyc_files as kf','kp.kyc_file_id','kf.kyc_file_id')
                            ->where('fintech_provider','UMSI')                                 
                            ->where(db::raw('DATE(kf.date_uploaded)'),db::raw('CURDATE()'))                                 
                            ->where('kp.program_id',session('Default_Program_Id'))
                            ->value('count_ussc');

         $count_files_today =db::table('kyc_files')          
                            ->select(db::raw('count(kyc_file_id) as count_files_stoday'))                               
                            ->where(db::raw('DATE(date_uploaded)'),db::raw('CURDATE()'))                                 
                            ->where('kp.program_id',session('Default_Program_Id'))
                            ->value('count_files_stoday');

        $count_records_today =db::table('kyc_profiles as kp')          
                            ->select(db::raw('count(kyc_id) as count_records_stoday'))  
                            ->join('kyc_files as kf','kp.kyc_file_id','kf.kyc_file_id')                             
                            ->where(db::raw('DATE(kf.date_uploaded)'),db::raw('CURDATE()'))                                 
                            ->where('kp.program_id',session('Default_Program_Id'))
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
                            ->where('kp.program_id',session('Default_Program_Id'))
                            ->value('count_spti');

        $count_ussc =db::table('kyc_profiles as kp')
                            ->select(db::raw('count(kyc_id) as count_ussc'))
                            ->join('kyc_files as kf','kp.kyc_file_id','kf.kyc_file_id')
                            ->where('fintech_provider','UMSI')                    
                            ->where('kp.program_id',session('Default_Program_Id'))                                         
                            ->value('count_ussc');

         $count_files =db::table('kyc_files')          
                            ->select(db::raw('count(kyc_file_id) as count_files'))                                                           
                            ->value('count_files');

        $count_records =db::table('kyc_profiles as kp')          
                            ->select(db::raw('count(kyc_id) as count_records'))  
                            ->join('kyc_files as kf','kp.kyc_file_id','kf.kyc_file_id')                                                      
                            ->where('kp.program_id',session('Default_Program_Id'))   
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
                                            'gm.prov_name',
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
                                ->join(DB::raw('(
                                    select * from geo_map group by iso_prv
                                    ) as gm'),'db.prv_code','gm.iso_prv')                                                                
                                ->orderBy('db.created_at','desc')                                
                                ->get();
        return Datatables::of($get_records)->make(true);
    }

    // show more generated disbursement details
    public function disbursement_generated_show_more($dbp_batch_id){
  
        
        $get_records = db::table('kyc_profiles as kp')
                                ->select(
                                    'rsbsa_no',
                                    db::raw("CONCAT(first_name,' ',last_name) as full_name"),
                                    db::raw("CONCAT(IF(street_purok = '-' OR street_purok = '', '' , CONCAT(street_purok,', ')),'BRGY. ',barangay,', ',province,', ',region) as address"),
                                    'fintech_provider',
                                    'kyc_id',
                                    'account_number',                         
                                    DB::raw('date_uploaded'),
                                    'region'                                    
                                )  
                                ->where('kp.program_id',session('Default_Program_Id'))
                                ->where('dbp_batch_id',$dbp_batch_id)                                                                                                               
                                ->get();
        return Datatables::of($get_records)->make(true);
    }

      // list of generated disbursement by file name
    public function list_of_generated_disbursement_by_file_name(){


        $get_records = db::table('kyc_profiles as kp')
                            ->select('kf.file_name','db.approved_batch_seq as batch_number',DB::raw('SUM(5070) as total_amount'),'total_rows',DB::raw('COUNT(kp.kyc_id) as total_records'),'db.date_approved','gr.region','gm.prov_name')
                            ->join('kyc_files as kf','kf.kyc_file_id','kp.kyc_file_id')
                            ->join('dbp_batch as db','kp.dbp_batch_id','db.dbp_batch_id')
                            ->join('geo_region as gr','gr.code_reg','db.reg_code')                                                                
                            ->join(DB::raw('(
                                select * from geo_map group by iso_prv
                                ) as gm'),'db.prv_code','gm.iso_prv')                                                                
                            ->where('kp.program_id',session('Default_Program_Id'))
                            ->orderBy('db.date_approved','DESC')
                            ->groupBy(['db.approved_batch_seq'])                            
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


        $count_success  = 0;
        
        foreach($file as $key => $item_file){
         


            if(is_file($item_file)){
            $get_filename = $item_file->getClientOriginalName();
            $check_file_exist = db::table('ingest_files')->where('file_name',$get_filename)->take(1)->get();
            $get_programs = db::table('programs')->where('status','1')->get();
                
            
            foreach($get_programs as $program_value){
                
                $check_program_filename =  str_contains($get_filename,trim($program_value->shortname));
                
                if($check_program_filename){
                    $count_success++;
                }

            }
                                
            
            if($count_success > 0 ){
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
            }else{
                $result = json_encode(["message" => 'filename error']);
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

       
      


        $file_name = request('file_name');
        $agency_id = request('agency_id');
        $provider  = '';
        $result = '';
        $upload_path = 'temp_excel/kyc';
        
        
      
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
        $count_success = 0;
        $count = '';
        $count_error = 0 ;
        $get_filename = $item_filename;


        $get_programs = db::table('programs')->where('status','1')->get();
        $program_id = '';
        foreach($get_programs as $program_value){
            
            $check_program_filename =  str_contains($get_filename,$program_value->shortname);

            if($check_program_filename){
                $program_id = $program_value->program_id;
            }

        }
        
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
    
                $kyc_import = new KYCImport($provider,$get_filename,$agency_id,$program_id);
                
                                                    
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


              
                }else{
                    return 'false';
                }
            }else{
                $count_error++;
                echo  json_encode(["message" => 'filename error']);
             
                
            }
        // }
    }

    if($count_error == 0){
        echo  json_encode(["message" => 'true','error_data' => $error_storage]);
        
    
    }else{
        echo  json_encode(["message" => 'false']);
        
    }
    

    }

    // GET INGESTED FILES
    public function get_ingested_files(){

        $get_records = db::table('kyc_files as kf')
                            ->select('kf.date_uploaded','kp.kyc_file_id','file_name','agency_name','kf.total_inserted','kf.total_rows')
                            ->leftJoin('kyc_profiles as kp','kf.kyc_file_id','kp.kyc_file_id')
                            ->leftJoin('agency as a','a.agency_id','kp.agency_id')
                            ->where('total_inserted','!=','0')
                            ->groupBy('kf.file_name')
                            ->orderBy('kf.date_uploaded')
                            ->get();

        return Datatables::of($get_records)->make(true);
    }

    // UPDATE FILE AGENCY
    public function update_agency(){

        try{

            $result = '';
            $message= '';

            $kyc_file_id = request('kyc_file_id');
            $agency_id = request('agency_id');

            $update_agency = db::table('kyc_profiles')
                                ->where('kyc_file_id',$kyc_file_id)
                                ->update(['agency_id'=>$agency_id]);
            
            if($update_agency){
                $result = 'true';
                $message = 'Successfully updated the agency.';
            }else{
                $result = 'false';
                $message = 'No changes has been made.';
            }

            return json_encode(['message'=>$message,"result"=>$result]);
        }catch(\Exception $e ){

            return json_encode(['message'=>$e->getMessage(),"result"=>'false']);
        }
    }



}
