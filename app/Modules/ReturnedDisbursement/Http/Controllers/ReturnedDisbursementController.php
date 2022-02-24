<?php

namespace App\Modules\ReturnedDisbursement\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Session;
use DB;
use ElephantIO\Client;
use ElephantIO\Engine\SocketIO\Version2X;
use Ramsey\Uuid\Uuid;
class ReturnedDisbursementController extends Controller
{

    /**
     * Display the module welcome screen
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        
        return view('ReturnedDisbursement::returned-disbursement-uploading');        
    }   

    public function ingest_files($file,$file_last_id,$filename,$db_total_rows,$token){
        try{

        $client = new Client(new Version2X(request()->getHost().':8080'));   
        $client->initialize();
        
        $get_file             = explode("\n", file_get_contents($file));
        $error_array          = [];
        $total_saved_records  = 0 ;             
        $delimiter            = '|';
        $imc_array            = [];

        foreach($get_file as $item){

            $row = explode($delimiter,$item);
            array_push($imc_array, $row);                
        }

        $total_records = count($imc_array);

        $compute_percentage = (1 / $total_records ) * 100;
        $sum_percentage = 0;

        // update total rows
         db::table('dbp_returned_files')   
                ->where('return_file_id',$file_last_id)
                ->update(["total_rows" => $total_records]);

        
        // insert to dbp_return file
        foreach($imc_array as $key => $item){

            $sum_percentage += $compute_percentage;            
            $client->emit('message', ['percentage' => round($sum_percentage,2), 'room' => $token]);
        
            
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
            
            

            // get region to check rsbsa
            $check_reg_prov =  db::table('geo_map')
                                 ->select('reg_code','prov_code','mun_code','geo_code','bgy_code')                            
                                 ->where('prov_name',$province)                                                          
                                 ->where('mun_name',$city_municipality)   
                                 ->take(1)                                             
                                 ->first(); 

            // get rsbsa to check rsbsa
            $check_rsbsa = db::table('dbp_return')
                             ->where('rsbsa_no',$rsbsa_no)->first();

            $check_account_number = db::table('dbp_return')->select('account_number')->where('account_number',$account_number)->first();

            
            // check if rsbsa exist, PSGC exist 
            if( !$check_rsbsa && $check_reg_prov && !$check_account_number){

                // insert row to dbp return
                $insert_dbp_return = db::table('dbp_return')
                                        ->insert([
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
                    "reg_code"              => $check_reg_prov->reg_code,
                    "prov_code"             => $check_reg_prov->prov_code,                                               
                    "mun_code"              => $check_reg_prov->mun_code,
                    "bgy_code"              => $check_reg_prov->bgy_code,
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
        
        $client->close();

        return ['total_saved_records' => $total_saved_records , 'total_records' => $total_records,"message"=>'true',"error_array" => $error_array];
    }catch(\Exception $e){

        var_dump($e);
    }
    }

    // upload files 
    public function upload_file(){


        try{


        

        $file = request()->file('dbp_returned_file');
        $token = request('token');

        
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
            $get_filename = $item_file->getClientOriginalName();    

            $check_file_exist = db::table('dbp_returned_files')->where('file_name',$get_filename)->take(1)->get();
          
            if(is_file($item_file)){
            
            
            
            
            
            if($check_file_exist->isEmpty()){


                
                $last_id = db::table('dbp_returned_files')->insertGetId(['file_name' => $get_filename ,'created_by_user_id' => session('user_id') ]);

                var_dump($last_id);
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
                    $ingest_file = $this->ingest_files($file_to_ingest,$last_id,$get_filename,$total_records,$token);

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
                
                $ingest_file = $this->ingest_files($file_to_ingest,$last_id,$get_filename,$total_records,$token);
                array_push($completed_array,$ingest_file);
            }

            return $completed_array;

            }
        }
    }catch(\Exception $e){

        var_dump($e);
    }
    }

    

}
