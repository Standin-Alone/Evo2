<?php

namespace App\Imports;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Session;
use Maatwebsite\Excel\Concerns\ToCollection;
use DB;
use Ramsey\Uuid\Uuid;
use Carbon\Carbon;
use ElephantIO\Client ;
use ElephantIO\Engine\SocketIO\Version2X;
class IMCImport implements ToCollection
{

    protected $file_name;
    private $total_rows = 0;
    private $message = '';
    private $inserted_count;
    private $error_data;
    public function __construct( $file_name){

        $this->file_name = $file_name;    
       
        
    }


    /**
    * @param Collection $collection
    */
    public function collection(Collection $collection)
    {
        try{    

        
        $client = new Client(new Version2X('http://192.168.1.8:7980'));   
        $client->initialize();
        $rows_inserted = 0;
        $collection_count = $collection->count();
        $file_name = $this->file_name;
        $error_data = [];        
        $test= [];
        $compute_percentage = (1 / $collection_count ) * 100;

        $check_filename = db::table('imc_files')->select('imc_file_id')->where('file_name',$file_name)->orderBy('date_created','DESC')->first();
              
                   
                          
              

        if($check_filename){ 

          $validate_filename = db::table('imc_files')->select('imc_file_id')->where('imc_file_id',$check_filename->imc_file_id)->whereColumn('total_inserted','total_rows')->orderBy('date_created','DESC')->first();
              if(!$validate_filename){
                  $get_imc_file_id = db::table('imc_files')
                  ->insertGetId([
                      "file_name" => $file_name,
                      "total_rows" => $collection_count,
                  ]);
              }else{
                  $get_imc_file_id = $check_filename->imc_file_id;
              }                  
        }else{
              $check_filename_again = db::table('imc_files')->select('imc_file_id')->where('file_name',$file_name)->orderBy('date_created','DESC')->first();
              if(!$check_filename_again){
                  $get_imc_file_id = db::table('imc_files')
                      ->insertGetId([
                          "file_name" => $file_name,
                          "total_rows" => $collection_count,
                      ]);

              }else{
                  $get_imc_file_id = $check_filename_again->imc_file_id;
              }
            
        }
        $sum_percentage = 0;

        foreach($collection as $key => $item){

            $sum_percentage += $compute_percentage;


            
            $client->emit('message', ['percentage' => $sum_percentage, 'room' => session('uuid')]);
            
            // echo $item[0]; //Currency
            // echo $item[1]; // transaction date
            // echo $item[2]; // Card Type
            // echo $item[3]; // Account Number
            // echo $item[4]; // Amount
            // echo $item[5]; // RSBSA Number
            // echo $item[6]; // Provider
            // echo $item[7]; // First Name
            // echo $item[8]; // Middle Name
            // echo $item[9]; // Last Name
            // echo $item[10]; // Street
            // echo $item[11]; // Barangay
            // echo $item[12]; // Province
            // echo $item[14]; // Mobile
            // echo $item[16]; // Region
            // echo $item[17] ; //remitter_name_1
            // echo $item[18]; //remitter_name_2
            // echo $item[19]; //program_name
            // echo $item[21]; // Remitter Location
            // echo $item[22]; // Remitter   Municipality  
            // echo $item[23]; // Remitter Province
            // echo $item[24]; // Status
            // echo $item[26]; // Transcode

            $rsbsa_no = $item[5];
            $account_number = $item[3];

            $check_rsbsa_no = db::table('imc_profiles')->select('rsbsa_no')->where('rsbsa_no',trim($rsbsa_no))->first();
            $check_account_number = db::table('imc_profiles')->select('account_number')->where('account_number',$account_number)->first();

            $format_transaction_date = strpos($item[1], '/') || is_int($item[1]) ? date('Y-m-d',strtotime($item[1])) : $item[1];

            $uuid                = Uuid::uuid4(); 
            $currency =  $item[0]; //Currencys
            $transaction_date =  $item[1]; // transaction date
            $card_type =  $item[2]; // Card Type
            $account_number = $item[3]; // Account Number
            $amount =  $item[4]; // Amount
            $rsbsa_no =  $item[5]; // RSBSA Number
            $provider = $item[6]; // Provider
            $first_name = $item[7]; // First Name
            $middle_name =  $item[8]; // Middle Name
            $last_name = $item[9]; // Last Name
            $street_purok =  $item[10]; // Street
            $barangay = $item[11]; // Barangay
            $province = $item[12]; // Province
            $mobile_no =  $item[14]; // Mobile
            $region =  $item[16]; // Region
            $remitter_name_1 = $item[17] ; //remitter_name_1
            $remitter_name_2 = $item[18]; //remitter_name_2
            $program_name = $item[19]; //program_name
            $remitter_location =$item[21]; // Remittance Location
            $remitter_mun = $item[22]; // Remitter   Municipality  
            $remitter_province = $item[23]; // Remitter Province
            $status = $item[24]; // Status
            $trans_code = $item[26]; // Transcode




            // check if rsbsa no exist and account number
            if(!$check_rsbsa_no && !$check_account_number && !is_null($first_name) && !is_null($last_name)  && !is_null($status)){

            
            // insert records to imc profiles
            $insert_imc = db::table('imc_profiles')
            ->insert([               
                
                    "imc_id" => $uuid,
                    "imc_file_id" => $get_imc_file_id,
                    "currency" =>   $currency, 
                    "transaction_date" =>   $format_transaction_date,
                    "card_type" =>   $card_type, 
                    "account_number" =>  $account_number, 
                    "amount" =>   $amount, 
                    "rsbsa_no" =>   $rsbsa_no, 
                    "provider" =>  $provider, 
                    'first_name'          => preg_replace('/[0-9]+/','',str_replace("Ñ","N", mb_strtoupper($first_name,'UTF-8'))),
                    'middle_name'         => preg_replace('/[0-9]+/','',str_replace("Ñ","N", mb_strtoupper($middle_name == '' ? 'NMN' : $middle_name,'UTF-8')))  ,
                    'last_name'           => preg_replace('/[0-9]+/','',str_replace("Ñ","N", mb_strtoupper($last_name,'UTF-8'))),
                    "street_purok" =>   str_replace("Ñ","N", mb_strtoupper($street_purok,'UTF-8')),
                    "barangay" =>  str_replace("Ñ","N", mb_strtoupper($barangay,'UTF-8')) ,
                    "province" =>  str_replace("Ñ","N", mb_strtoupper($province,'UTF-8')) , 
                    "mobile_no" =>  $mobile_no, 
                    "region" =>   str_replace("Ñ","N", mb_strtoupper($region,'UTF-8')) , 
                    "remitter_name_1" =>  $remitter_name_1 ,
                    "remitter_name_2" =>  $remitter_name_2, 
                    "program_name" =>  $program_name,
                    "remittance_location" => str_replace("Ñ","N", mb_strtoupper($remitter_location,'UTF-8')) , 
                    "remittance_mun" =>  str_replace("Ñ","N", mb_strtoupper($remitter_mun,'UTF-8')) , 
                    "remittance_province" =>  str_replace("Ñ","N", mb_strtoupper($remitter_province,'UTF-8')) , 
                    "status" =>  $status,
                    "trans_code" =>  $trans_code,
                    'uploaded_by_user_id' => session('uuid'),
                    'uploaded_by_user_fullname'  => str_replace("Ñ","N", mb_strtoupper(session('first_name'),'UTF-8')).' '.str_replace("Ñ","N", mb_strtoupper(session('last_name'),'UTF-8'))
            ]);


            if($insert_imc){
                                                               
                ++$rows_inserted;
            
            }

            }
            // error remarks
            else{

                $error_remarks = '';
                // set error remarks
                if($account_number == '')
                {
                    $error_remarks = 'No account number';
                }

                if($rsbsa_no == ''){
                    $error_remarks = ($error_remarks == ''  ? 'No RSBSA number' : $error_remarks.','.'No RSBSA number');
                }
                if($status == ''){
                    $error_remarks = ($error_remarks == ''  ? 'Status' : $error_remarks.','.'Status');
                }

                if($check_rsbsa_no ){
                    $error_remarks = ($error_remarks == ''  ? 'Duplicate RSBSA number' : $error_remarks.','.'Duplicate RSBSA number');
                }

                if($first_name == '' && $last_name == '' ){
                    $error_remarks = ($error_remarks == ''  ? 'Incomplete name' : $error_remarks.','.'Incomplete name');
                }


                
                // if(!$check_reg_prov){
                //     $error_remarks = ($error_remarks == ''  ? 'Incomplete or wrong spelling of address' : $error_remarks.','.'Incomplete or wrong spelling of address');
                // }

                if($check_account_number){
                    $error_remarks = ($error_remarks == ''  ? 'Duplicate account number' : $error_remarks.','.'Duplicate account number');
                }

                // error data
                $data = [
                    "imc_file_id" => $uuid,
                    "currency" =>   $currency, 
                    "transaction_date" =>   $transaction_date,
                    "card_type" =>   $card_type, 
                    "account_number" =>  $account_number, 
                    "amount" =>   $amount, 
                    "rsbsa_no" =>   $rsbsa_no, 
                    "provider" =>  $provider, 
                    'first_name'    => $first_name,
                    'middle_name'    => $middle_name == '' ? 'NMN' : $middle_name  ,
                    'last_name'           => $last_name,
                    "street_purok" =>  $street_purok,
                    "barangay" => $barangay ,
                    "province" => $province , 
                    "mobile_no" =>  $mobile_no, 
                    "region" =>  $region , 
                    "remitter_name_1" =>  $remitter_name_1 ,
                    "remitter_name_2" =>  $remitter_name_2, 
                    "program_name" =>  $program_name,
                    "remittance_location" =>$remitter_location , 
                    "remittance_mun" => $remitter_mun , 
                    "remittance_province" => $remitter_province , 
                    "status" =>  $status,
                    "trans_code" =>  $trans_code,          
                    'remarks'             => $error_remarks,
                    'file_name'             => $file_name,    
                ];

                array_push($error_data,$data);
            }

        

        }   
        
        $this->error_data = $error_data;
        $this->inserted_count = $rows_inserted;
        $this->total_rows = $collection->count();
        $this->message = 'true';

         // update total inserted in imc file table
        if($rows_inserted != 0 ) {
            $get_total_inserted = db::table('imc_files')->where('imc_file_id',$get_imc_file_id)->orderBy('date_created','DESC')->first()->total_inserted;
            db::table('imc_files')->where('imc_file_id',$get_imc_file_id)->update(["total_inserted" => $get_total_inserted + $rows_inserted,"total_rows"=>$collection_count]);
        }
        }catch(\Exception $e){
            $this->message = json_encode($e->getMessage());
            // $this->message = 'false';
        }
    }


    public function get_result(){
        return ['total_rows_inserted' => $this->inserted_count , 'total_rows' => $this->total_rows,"message"=>$this->message,"error_data" => $this->error_data];

    }       
}
