<?php

namespace App\Modules\ReturnedDisbursement\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Session;
use DB;
use Yajra\DataTables\Facades\DataTables;
use ElephantIO\Client;
use ElephantIO\Engine\SocketIO\Version2X;
use Ramsey\Uuid\Uuid;
use App\Models\GlobalNotificationModel;
class ReturnedDisbursementController extends Controller
{   


    public function __construct(){
        $this->middleware('session.module');
        $this->middleware('session.notifications');
     
    }

    /**
     * Display the module welcome screen
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $get_agency = db::table('agency')->get();
        return view('ReturnedDisbursement::returned-disbursement-uploading',compact('get_agency'));        
    }   

    public function ingest_files($agency_id,$program_id,$file,$file_last_id,$filename,$db_total_rows,$token){
        try{
        //     $options = [
        //         'context' => [
        //             'ssl' => [
        //                 'verify_peer' => false,
        //                 'verify_peer_name' => false
        //             ]
        //         ]
        //     ];
        // $client = new Client(new Version2X('https://imp-app.da.gov.ph'.':8080',$options));   
        // $client->initialize();
        
        $get_file             = explode("\n", file_get_contents($file));
        $error_array          = [];
        $total_saved_records  = 0 ;             
        $delimiter            = '|';
        $imc_array            = [];

        foreach($get_file as $item){
            
            if(!empty($item)){
                $row = explode($delimiter,$item);
                array_push($imc_array, $row);                
            }
            
        }

        $total_records = count($imc_array);

        $compute_percentage = (1 / $total_records ) * 100;
        $sum_percentage = 0;

        // update total rows
         db::table('dbp_returned_files')   
                ->where('return_file_id',$file_last_id)
                ->update(["total_rows" => $total_records]);

        $region_code = '';
        $program = '';
        // insert to dbp_return file
        foreach($imc_array as $key => $item){

            $sum_percentage += $compute_percentage;            
            // $client->emit('message', ['percentage' => round($sum_percentage,2), 'room' => $token, 'filename' => $filename]);
        
            
            $date                  = Carbon::parse($item[1]);
            $funding_currency      = $item[0];
            $imc                   = $item[2];
            $account_number        = $item[3];
            $amount                = $item[4];
            $rsbsa_no              = $item[5];
            $fintech_provider      = $item[6];
            $last_name             = $item[7];
            $first_name            = $item[8];
            $middle_name           = $item[9];
            $street_purok          = $item[10];
            $city_municipality     = $item[11];
            $province              = $item[12];
            $beneficiary_telnum    = $item[13];
            $contact_num           = $item[14];
            $message               = $item[15];
            $remitter_name_1       = $item[16];
            $remitter_name_2       = $item[17];
            $remitter_name_3       = $item[18];
            $remitter_id           = $item[19];
            $beneficiary_id        = $item[20];
            $remitter_address_1    = $item[21];
            $remitter_city         = $item[22];
            $remitter_province     = $item[23];
            $dbp_status            = $item[24];

            //additional column
            $remarks               = array_key_exists(25, $item) ? $item[25] : '';
            $dbp_record_id         = array_key_exists(26, $item) ? $item[26] : '';
            
            

            // get region to check rsbsa
            $check_reg_prov =  db::table('geo_map')
                                 ->select('reg_code','prov_code','mun_code','geo_code','bgy_code','bgy_name','reg_name')                                                             
                                 ->where('prov_name',$province)                                                          
                                 ->where('mun_name',$city_municipality)                                                                             
                                 ->first(); 

            // get rsbsa to check rsbsa
            $check_rsbsa = db::table('dbp_return')
                             ->where('program_id',$program_id)
                             ->where('rsbsa_no',$rsbsa_no)->first();

            $check_account_number = db::table('dbp_return')->select('account_number')->where('account_number',$account_number)->where('program_id',$program_id)->first();

            
            // check if rsbsa exist, PSGC exist 
            if( !$check_rsbsa && $check_reg_prov && !$check_account_number && $account_number != '' && $first_name != '' && $last_name != '' && $program_id != '' && $agency_id != '' && $dbp_record_id != ''  ){
                // region code
                $region_code = $check_reg_prov->reg_code;
                $program = $program_id;
                // insert row to dbp return
                $insert_dbp_return = db::table('dbp_return')
                                        ->insert([
                                            "return_file_id"        => $file_last_id,
                                            "program_id"            => $program_id,
                                            "agency_id"             => $agency_id,
                                            "date"                  => $date,
                                            "funding_currency"      => $funding_currency,
                                            "imc"                   => $imc,
                                            "account_number"        => $account_number,
                                            "amount"                => $amount,
                                            "rsbsa_no"              => $rsbsa_no,
                                            "fintech_provider"      => $fintech_provider,
                                            "last_name"             => preg_replace('/[0-9]+/','',str_replace("Ñ","N", mb_strtoupper($last_name,'UTF-8'))),
                                            "first_name"            => preg_replace('/[0-9]+/','',str_replace("Ñ","N", mb_strtoupper($first_name,'UTF-8'))),
                                            "middle_name"           => preg_replace('/[0-9]+/','',str_replace("Ñ","N", mb_strtoupper($middle_name == '' ? 'NMN' : $middle_name,'UTF-8')))  ,
                                            "street_purok"          => str_replace("Ñ","N", mb_strtoupper($street_purok,'UTF-8')),
                                            "city_municipality"     => str_replace("Ñ","N", mb_strtoupper($city_municipality,'UTF-8')) ,
                                            "province"              => str_replace("Ñ","N", mb_strtoupper($province,'UTF-8')),
                                            "beneficiary_telnum"    => $beneficiary_telnum,
                                            "contact_num"           => $contact_num,
                                            "message"               => $message,
                                            "remitter_name_1"       => $remitter_name_1,
                                            "remitter_name_2"       => $remitter_name_2,
                                            "remitter_name_3"       => $remitter_name_3,
                                            "remitter_id"           => $remitter_id,
                                            "beneficiary_id"        => $beneficiary_id,
                                            "remitter_address_1"    => str_replace("Ñ","N", mb_strtoupper($remitter_address_1,'UTF-8')),
                                            "remitter_city"         => str_replace("Ñ","N", mb_strtoupper($remitter_city,'UTF-8')),
                                            "remitter_province"     => str_replace("Ñ","N", mb_strtoupper($remitter_province,'UTF-8')),
                                            "dbp_status"            => $dbp_status,
                                            "remarks"               => $remarks,
                                            "dbp_record_id"         => $dbp_record_id,
                                            "reg_code"              => $check_reg_prov->reg_code,
                                            "prov_code"             => $check_reg_prov->prov_code,                                               
                                            "mun_code"              => $check_reg_prov->mun_code,
                                            "bgy_code"              => $check_reg_prov->bgy_code
                                        ]);     
                if($insert_dbp_return){
                    $total_saved_records++;
                }
                
            }else{  

                // error display start here...
                $error_remarks = '';

                if($rsbsa_no == ''){
                    $error_remarks = ($error_remarks == ''  ? 'No RSBSA number' : $error_remarks.','.'No RSBSA number');
                }   

                if(!$check_reg_prov){
                    $error_remarks = ($error_remarks == ''  ? 'Incomplete or wrong spelling of address' : $error_remarks.','.'Incomplete or wrong spelling of address');
                }

                
                if($first_name == '' || $last_name == '' ){
                    $error_remarks = ($error_remarks == ''  ? 'Incomplete name' : $error_remarks.','.'Incomplete name');
                }

                if($account_number == ''){
                    $error_remarks = ($error_remarks == ''  ? 'No account number' : $error_remarks.','.'No account number');
                }
                
                if($program_id == ''){
                    $error_remarks = ($error_remarks == ''  ? 'No program' : $error_remarks.','.'No program');
                }

                if($agency_id == ''){
                    $error_remarks = ($error_remarks == ''  ? 'No agency' : $error_remarks.','.'No agency');
                }

                if($dbp_record_id == ''){
                    $error_remarks = ($error_remarks == ''  ? 'No DBP Record ID' : $error_remarks.','.'No DBP Record ID');
                }
                
                if($check_account_number){
                    $error_remarks = ($error_remarks == ''  ? 'Duplicate account number' : $error_remarks.','.'Duplicate account number');
                }


                $error_data = [
                    "return_file_id"        => $file_last_id,
                    "date"                  => $date,
                    "funding_currency"      => $funding_currency,
                    "imc"                   => $imc,
                    "account_number"        => $account_number,
                    "amount"                => $amount,
                    "rsbsa_no"              => $rsbsa_no,
                    "fintech_provider"      => $fintech_provider,
                    "last_name"             => $last_name,
                    "first_name"            => $first_name,
                    "middle_name"           => $middle_name,
                    "street_purok"          => $street_purok,
                    "city_municipality"     => $city_municipality,
                    "province"              => $province,
                    "beneficiary_telnum"    => $beneficiary_telnum,
                    "contact_num"           => $contact_num,
                    "message"               => $message,
                    "remitter_name_1"       => $remitter_name_1,
                    "remitter_name_2"       => $remitter_name_2,
                    "remitter_name_3"       => $remitter_name_3,
                    "remitter_id"           => $remitter_id,
                    "beneficiary_id"        => $beneficiary_id,
                    "remitter_address_1"    => $remitter_address_1,
                    "remitter_city"         => $remitter_city,
                    "remitter_province"     => $remitter_province,
                    "dbp_status"            => $dbp_status,
                    "reg_code"              => isset($check_reg_prov->reg_code)  ? $check_reg_prov->reg_code  : '' ,
                    "prov_code"             => isset($check_reg_prov->prov_code) ? $check_reg_prov->prov_code : '' ,                                               
                    "mun_code"              => isset($check_reg_prov->mun_code)  ? $check_reg_prov->mun_code  : '',
                    "bgy_code"              => isset($check_reg_prov->bgy_code)  ? $check_reg_prov->bgy_code  : '',
                    "region"                => isset($check_reg_prov->reg_name)  ? $check_reg_prov->reg_name  : '',
                    "barangay"              => isset($check_reg_prov->bgy_name)  ? $check_reg_prov->bgy_name  : '',                   
                    "remarks"               => $error_remarks,
                    "file_name"             => $filename,
                ];

                array_push($error_array,$error_data);
            }                       
        }


        // update total saved reocrds
        if($total_saved_records !=0){
            $get_last_total_saved_records = db::table('dbp_returned_files')->where('return_file_id',$file_last_id)->orderBy('date_uploaded','DESC')->first()->total_inserted;
            
            db::table('dbp_returned_files')   
                ->where('return_file_id',$file_last_id)
                ->update(["total_inserted" => $total_saved_records + $get_last_total_saved_records]);
        }


        $upload_path = 'temp_text_file/returned_disbursement_files';

        
        $upload_folder  = $upload_path.'/'.Carbon::now()->year;

        // create folder for returned disbursement files;
        if(!File::isDirectory($upload_path)){
            
            File::makeDirectory($upload_path, 0775, true);                                
            $by_year_path = $upload_path.'/'.Carbon::now()->year;
            if(!File::isDirectory($by_year_path)){

                File::makeDirectory($by_year_path, 0775, true);
            }
        }else{
            $by_year_path = $upload_path.'/'.Carbon::now()->year;
            if(!File::isDirectory($by_year_path)){

                File::makeDirectory($by_year_path, 0775, true);
            }
        }



        // upload error log files
        $serialize_data = response()->json($error_array)->getContent();
        $error_log_name = pathinfo($filename, PATHINFO_FILENAME);        
        Storage::disk('temp_text_file')->put($upload_folder.'/'.$error_log_name.'-error.json',$serialize_data);


        // $client->close();
        $role_id = 10; 

        $consolidate_notification = [];

        $message = "You have new ".$total_saved_records." records of returned disbursement.";
        $title   = "New Returned Disbursement KYC Profiles.";
        $link    = route('CancellationModule.index');
        
        $get_user = db::table('users as u')
                                ->join('program_permissions as pp','u.user_id','pp.user_id')
                                ->where('role_id',$role_id)
                                ->where('reg',$region_code)
                                ->where('agency',$agency_id)
                                ->where('program_id',$program_id)
                                ->groupBy('u.user_id')
                                ->get();  
        
        foreach($get_user as $user_info){
            
            $notification = GlobalNotificationModel::sendNotification($user_info->user_id,$title,$message,$link);

            array_push($consolidate_notification,$notification);
        }           

        


        return ['total_saved_records' => $total_saved_records , 'total_records' => $total_records,"message"=>'true',"error_array" => $error_array,"notification" => $consolidate_notification ];
    }catch(\Exception $e){

        return json_encode($e->getMessage());
    }
    }

    // upload files 
    public function upload_file(){


        try{


        

        $file = request()->file('dbp_returned_file');
        $token = request('token');
        $agency_id = request('agency_id');
        
        
        $upload_path = 'temp_text_file/returned_disbursement_files';

        $completed_array = [];
        $upload_folder  = $upload_path.'/'.Carbon::now()->year;

        // create folder for returned disbursement files;
        if(!File::isDirectory($upload_path)){
            
            File::makeDirectory($upload_path, 0775, true);                                
            $by_year_path = $upload_path.'/'.Carbon::now()->year;
            if(!File::isDirectory($by_year_path)){

                File::makeDirectory($by_year_path, 0775, true);
            }
        }else{
            $by_year_path = $upload_path.'/'.Carbon::now()->year;
            if(!File::isDirectory($by_year_path)){

                File::makeDirectory($by_year_path, 0775, true);
            }
        }

        

        
        

        foreach($file as $key => $item_file){
            $count_success = 0;    
            $get_filename = $item_file->getClientOriginalName();    

            $check_file_exist = db::table('dbp_returned_files')->where('file_name',$get_filename)->take(1)->get();
            $get_programs = db::table('programs')->where('status','1')->get();
            $get_program_id = '';
            $fuel_strings_to_check = ['FCRN','FBFR'];
            foreach($get_programs as $program_value){
                
                $check_program_filename =  str_contains($get_filename,trim($program_value->shortname));
                
                if($check_program_filename){
                    $count_success++;
                    $get_program_id = $program_value->program_id;

                    // CONSTANT DA AGENCY ID FOR OTHER PROGRAMS
                    $agency_id = db::table('agency')->where('agency_shortname', 'DA')->first()->agency_id; 
                }else{
                    // FUEL FILE NAME CHECK 
                    foreach($fuel_strings_to_check as $item_value){
                        $check_fuel_filename =  str_contains($get_filename,$item_value);

                        if($check_fuel_filename){
                            $count_success++;
                            $get_fuel_program_id = db::table('programs')->where('status',1)->where('shortname','FUEL')->first()->program_id;
                            $get_program_id = $get_fuel_program_id;
                            $agency_id = db::table('agency')->where('agency_shortname', $item_value == 'FBFR' ? 'BFAR' : 'DA')->first()->agency_id;
                        }
                    }
                }

            }
            

            if($count_success > 0 ){
                if(is_file($item_file)){                                                            
                    if($check_file_exist->isEmpty()){


                
                        $last_id = db::table('dbp_returned_files')->insertGetId(['file_name' => $get_filename ,'created_by_user_id' => session('user_id') ]);
                        
                        // check file name if it has fintech provider
                        if(str_contains($get_filename,'USSC')){

                            if(str_contains($get_filename,'USSC')){
                                $provider = 'UMSI';
                            }
                        
        
                    $upload_file =  Storage::disk('temp_text_file')->put($upload_folder.'/'.$get_filename,file_get_contents($item_file));
                    $re_check_file_exist = db::table('dbp_returned_files')->where('file_name',$get_filename)->take(1)->get();

                        //uploadfile
                        if($upload_file){   
                            $file_to_ingest = $upload_folder.'/'.$get_filename;
                            $total_records = $re_check_file_exist[0]->total_rows;
                            $ingest_file = $this->ingest_files($agency_id,$get_program_id,$file_to_ingest,$last_id,$get_filename,$total_records,$token);

                            array_push($completed_array,$ingest_file);
                        }   
                            
                            $result =  json_encode(["message" => 'true']);
                        }else{
                            $result = json_encode(["message" => 'filename error']);
                        }
                    }else{
                
                        $upload_file =  Storage::disk('temp_text_file')->put($upload_folder.'/'.$get_filename,file_get_contents($item_file));


                        $last_id = $check_file_exist[0]->return_file_id;
                        $total_records = $check_file_exist[0]->total_rows;

                        $file_to_ingest = $upload_folder.'/'.$get_filename;
                        
                        $ingest_file = $this->ingest_files($agency_id,$get_program_id,$file_to_ingest,$last_id,$get_filename,$total_records,$token);                
                        array_push($completed_array,$ingest_file);
                        
                    }

            

            }
        }else{
            $result = json_encode(["message" => 'filename error']);
        }
        }

        return json_encode($completed_array);
        
    }catch(\Exception $e){

        return json_encde($e->getMessage());
    }   

    
    }


    //get list of ingested files
    public function get_files(){
        $get_records = db::table('dbp_returned_files as drf')
                            ->select('drf.file_name',
                                'p.shortname',
                                'a.agency_shortname',
                                DB::raw('IF(total_inserted is NULL,0,total_inserted) as total_inserted'),
                                DB::raw('IF(total_rows is NULL,0,total_rows) as total_rows'),
                                'drf.date_uploaded',
                                'drf.return_file_id',
                                DB::raw(" (select CONCAT(first_name,' ',last_name) as full_name  from users where user_id = drf.created_by_user_id) as created_by"
                            ))    
                            ->join('dbp_return as dr','drf.return_file_id','dr.return_file_id')                            
                            ->join('agency as a','a.agency_id','dr.agency_id')                            
                            ->join('programs as p','p.program_id','dr.program_id')                            
                            ->where('created_by_user_id',session('user_id'))                 
                            ->groupBy('drf.file_name')                                                  
                            ->orderBy('drf.date_uploaded','DESC')                            
                            ->get();

        return Datatables::of($get_records)->make(true);                
    }
    
     // show more uploaded dbp returned files
     public function show_more($return_file_id){
  
        
        $get_records = db::table('dbp_return as dr')
                                ->select(
                                    'rsbsa_no',
                                    db::raw("CONCAT(first_name,' ',last_name) as full_name"),
                                    db::raw("CONCAT(IF(street_purok = '-' OR street_purok = '', '' , CONCAT(street_purok,', ')),'BRGY. ',
                                    (select bgy_name from geo_map where reg_code = dr.reg_code and mun_code = dr.mun_code and prov_code = dr.prov_code and bgy_code = dr.bgy_code),
                                    ',',
                                    (select prov_name from geo_map where reg_code = dr.reg_code and mun_code = dr.mun_code and prov_code = dr.prov_code and bgy_code = dr.bgy_code),
                                    ', ',
                                    (select reg_name from geo_map where reg_code = dr.reg_code and mun_code = dr.mun_code and prov_code = dr.prov_code and bgy_code = dr.bgy_code)
                                    ) 
                                    as address"),
                                    'fintech_provider',
                                    'return_file_id',
                                    'account_number',        
                                    'dbp_status',                         
                                    DB::raw('date_uploaded'),
                                    db::raw(' (select reg_name from geo_map where reg_code = dr.reg_code and mun_code = dr.mun_code and prov_code = dr.prov_code and bgy_code = dr.bgy_code) as region')                            
                                )  
                                ->where('return_file_id',$return_file_id)                                                                                                               
                                ->get();
        return Datatables::of($get_records)->make(true);
    }


    // show more uploaded dbp returned files
    public function show_error_logs($filename){
        
        $upload_path = 'temp_text_file/returned_disbursement_files';
        $clean_filename = $filename;
        $upload_folder  = $upload_path.'/'.Carbon::now()->year.'/'.$clean_filename.'-error.json';
        $get_records    =  json_decode(file_get_contents($upload_folder),true);
        
        return Datatables::of($get_records)->make(true);
    }

}
