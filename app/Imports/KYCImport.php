<?php

namespace App\Imports;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Session;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithStartRow;
use DB;
use Ramsey\Uuid\Uuid;
use Carbon\Carbon;
use App\Models\GlobalNotificationModel;
use ElephantIO\Client;
use ElephantIO\Engine\SocketIO\Version2X;
use File;
use Illuminate\Support\Facades\Storage;
class KYCImport implements ToCollection,WithStartRow
{

    private $inserted_count = 0;
    private $total_rows = 0;
    private $message = '';
    protected $provider;
    protected $file_name;
    protected $agency_id;
    protected $program_id;
    protected $client;

    private $error_data;
    private $region;
    private $region_code;
    
    public function __construct($provider, $file_name,$agency_id,$program_id){
        $this->provider = $provider;   
        $this->file_name = $file_name;
        $this->agency_id = $agency_id;    
        $this->program_id = $program_id;    
       
    
    }



    /**
    * @param Collection $collection
    */
    public function collection(Collection $collection)
    { 
     
        try{


    
 


        // $PRIVATE_KEY =  '3273357538782F413F4428472B4B6250655368566D5971337436773979244226452948404D635166546A576E5A7234753778214125442A462D4A614E64526755'.
        //                 '6A586E327235753778214125442A472D4B6150645367566B59703373367639792F423F4528482B4D6251655468576D5A7134743777217A25432646294A404E63'.
        //                 '5166546A576E5A7234753777217A25432A462D4A614E645267556B58703273357638792F413F4428472B4B6250655368566D597133743677397A244326452948'.
        //                 '2B4D6251655468576D5A7134743777397A24432646294A404E635266556A586E3272357538782F4125442A472D4B6150645367566B5970337336763979244226'.
        //                 '4428472B4B6250655368566D5971337436773979244226452948404D635166546A576E5A7234753778214125432A462D4A614E645267556B5870327335763879';

       
        


        $rows_inserted = 0;
        $provider = $this->provider;
        $file_name = $this->file_name;
        $agency_id = $this->agency_id;
        $program_id = $this->program_id;
        $collection_count = $collection->count();
        $region_for_mail = '';
        $region_code = '';
        $compute_percentage = (1 / $collection_count ) * 100;
        $error_data = [];        

        $get_kyc_file_id = '';

              // insert kyc files to database
                

              $check_filename = db::table('kyc_files')->select('kyc_file_id')->where('file_name',$file_name)->orderBy('date_uploaded','DESC')->first();
              
              
                          
              

              if($check_filename){ 

                $validate_filename = db::table('kyc_files')->select('kyc_file_id')->where('kyc_file_id',$check_filename->kyc_file_id)->whereColumn('total_inserted','total_rows')->orderBy('date_uploaded','DESC')->first();
                    if($validate_filename){
                        $get_kyc_file_id = db::table('kyc_files')
                        ->insertGetId([
                            "file_name" => $file_name,
                            "total_rows" => $collection_count,
                        ]);
                    }else{
                        $get_kyc_file_id = $check_filename->kyc_file_id;
                    }                  
              }else{
                    $check_filename_again = db::table('kyc_files')->select('kyc_file_id')->where('file_name',$file_name)->orderBy('date_uploaded','DESC')->first();
                    if(!$check_filename_again){
                        $get_kyc_file_id = db::table('kyc_files')
                            ->insertGetId([
                                "file_name" => $file_name,
                                "total_rows" => $collection_count,
                            ]);

                    }else{
                        $get_kyc_file_id = $check_filename_again->kyc_file_id;
                    }
                  
              }

        $sum_percentage = 0;



     

      

        foreach($collection as $key => $item){
        
     
            
            // progress_bar
            
       

            // check rsbsa no if exists
            $rsbsa_no   = $item[0];                
            $check_rsbsa_no = db::table('kyc_profiles')->select('rsbsa_no')->where('rsbsa_no',trim($rsbsa_no))->where('program_id',$program_id)->first();
            

            // calculate the progress of importing;
            // $sum_percentage += $compute_percentage;
            
            // emit to the socket io server
         
            
            
      

                        
                  
                    // insert to kyc profiles
                   
  

                        $format_birthday = strpos($item[13], '/') || is_int($item[13]) ? \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($item[13]) : $item[13];




                        $uuid                = Uuid::uuid4();                        
                        $fintech_provider    = $provider;    
                        $rsbsa_no            = trim($item[0]);    
                        $first_name          = trim($item[1]);            
                        $middle_name         = trim($item[2]);
                        $last_name           = trim($item[3]);
                        $ext_name            = trim($item[4]);
                        $id_number           = trim($item[5]);
                        $gov_id              = trim($item[6]);
                        $street_purok        = trim($item[7]);
                        $barangay            = trim($item[8]);
                        $municipality        = trim($item[9]);
                        $district            = trim($item[10]);                        
                        $province            = trim($item[11]);
                        $region              = trim($item[12]);
                        $birthdate           = $format_birthday;
                        $place_of_birth      = trim($item[14]);
                        $mobile_no           = trim($item[15]);
                        $sex                 = trim($item[16]);
                        $nationality         = trim($item[17]);
                        $profession          = trim($item[18]);
                        $sourceoffunds       = trim($item[19]);
                        $mothers_maiden_name = trim($item[20]);
                        $no_parcel           = trim($item[21]);
                        $total_farm_area     = trim($item[22]);
                        $account             = trim($item[23]);
                        $remarks             = is_null($item[23]) ? 'Failed' : trim($item[24]);

                        $check_reg_prov =  db::table('geo_map')
                                                ->select('reg_code','prov_code','mun_code')
                                                // ->where('bgy_name',$barangay)
                                                ->where('prov_name',preg_replace('~\x{00a0}~siu','',$province))
                                                ->where('reg_name',preg_replace('~\x{00a0}~siu','',$region) )                                                
                                                ->where('mun_name', preg_replace('~\x{00a0}~siu','',$municipality))   
                                                ->take(1)                                             
                                                ->first(); 
                                                
                        // $check_account_number = db::table('kyc_profiles')->where(DB::raw("AES_DECRYPT(account_number,'".$PRIVATE_KEY."')"),$account)->take(1)->get(); for encryption of accout number
                        $check_account_number = db::table('kyc_profiles')
                                                            ->select('account_number')                                                                
                                                            ->where('program_id',$program_id)
                                                            ->where('account_number',$account)   
                                                            ->first();
                        
                        
                        if(!$check_rsbsa_no && !$check_account_number  && $check_reg_prov && !is_null($item[23]) && !is_null($first_name) && !is_null($last_name) ){
                         
                            // set region for send email
                            $region_for_mail =  $region; 
                            

                            // $bgy_code   =  $check_reg_prov->bgy_code; for checking of barangay
                            $mun_code   =  $check_reg_prov->mun_code;
                            $prov_code   =  $check_reg_prov->prov_code;
                            $reg_code   =  $check_reg_prov->reg_code;
                            
                            // for send notification
                            $region_code = $reg_code;

               
                        
                        
                        
                        // this variable is for inserting of kyc profiles to database
                        $insert_kyc = db::table('kyc_profiles')
                                ->insert([
                                    'kyc_id'              => $uuid,
                                    'agency_id'           => $agency_id,
                                    'program_id'          => $program_id,
                                    'kyc_file_id'         => $get_kyc_file_id,                                    
                                    'fintech_provider'    => $fintech_provider,
                                    'rsbsa_no'            => $rsbsa_no,
                                    'first_name'          => preg_replace('/[0-9]+/','',str_replace("Ñ","N", mb_strtoupper($first_name,'UTF-8'))),
                                    'middle_name'         => preg_replace('/[0-9]+/','',str_replace("Ñ","N", mb_strtoupper($middle_name == '' ? 'NMN' : $middle_name,'UTF-8')))  ,
                                    'last_name'           => preg_replace('/[0-9]+/','',str_replace("Ñ","N", mb_strtoupper($last_name,'UTF-8'))),
                                    'ext_name'            => $ext_name,
                                    'id_number'           => $id_number,
                                    'gov_id_type'         => $gov_id,
                                    'street_purok'        => str_replace("Ñ","N", mb_strtoupper($street_purok,'UTF-8')),
                                    // 'bgy_code'            => $bgy_code,
                                    'barangay'            => str_replace("Ñ","N", mb_strtoupper($barangay,'UTF-8')) ,
                                    'mun_code'            => $mun_code,
                                    'municipality'        => str_replace("Ñ","N", mb_strtoupper($municipality,'UTF-8')),
                                    'district'            => $district,
                                    'prov_code'           => $prov_code,
                                    'province'            => str_replace("Ñ","N", mb_strtoupper($province,'UTF-8')),
                                    'reg_code'            => $reg_code,
                                    'region'              => str_replace("Ñ","N", mb_strtoupper($region,'UTF-8')),
                                    'birthdate'           => $birthdate,
                                    'place_of_birth'      => str_replace("Ñ","N", mb_strtoupper($place_of_birth,'UTF-8')),
                                    'mobile_no'           => (int)$mobile_no,
                                    'sex'                 => $sex,
                                    'nationality'         => str_replace("Ñ","N", mb_strtoupper($nationality,'UTF-8')),
                                    'profession'          => str_replace("Ñ","N", mb_strtoupper($profession,'UTF-8')),
                                    'sourceoffunds'       => str_replace("Ñ","N", mb_strtoupper($sourceoffunds,'UTF-8')),
                                    'mothers_maiden_name' => str_replace("Ñ","N", mb_strtoupper($mothers_maiden_name == '' ? 'NMMN' : $mothers_maiden_name,'UTF-8')),
                                    'no_parcel'           => $no_parcel,
                                    'total_farm_area'     => $total_farm_area,                                    
                                    'account_number'      => $account,
                                    'remarks'             => mb_strtoupper($remarks),
                                    'uploaded_by_user_id' => session('uuid'),
                                    'uploaded_by_user_fullname'  => str_replace("Ñ","N", mb_strtoupper(session('first_name'),'UTF-8')).' '.str_replace("Ñ","N", mb_strtoupper(session('last_name'),'UTF-8'))
                                ]);
                                
                            // this condition is for counting of rows inserted in kyc profiles
                            if($insert_kyc){
                                
                               
                                ++$rows_inserted;
                            
                            }

                        }else{  

                            
                            $error_remarks = '';
                            // set error remarks
                            if($account == '')
                            {
                                $error_remarks = 'No account number';
                            }

                            if($rsbsa_no == ''){
                                $error_remarks = ($error_remarks == ''  ? 'No RSBSA number' : $error_remarks.','.'No RSBSA number');
                            }

                            if($check_rsbsa_no ){
                                $error_remarks = ($error_remarks == ''  ? 'Duplicate RSBSA number' : $error_remarks.','.'Duplicate RSBSA number');
                            }

                            if($first_name == '' && $last_name == '' ){
                                $error_remarks = ($error_remarks == ''  ? 'Incomplete name' : $error_remarks.','.'Incomplete name');
                            }


                            
                            if(!$check_reg_prov){
                                $error_remarks = ($error_remarks == ''  ? 'Incomplete or wrong spelling of address' : $error_remarks.','.'Incomplete or wrong spelling of address');
                            }

                            if($check_account_number){
                                $error_remarks = ($error_remarks == ''  ? 'Duplicate account number' : $error_remarks.','.'Duplicate account number');
                            }

                            

                            // this data is for not inserted to database
                            $data = [
                            'kyc_id'              => $uuid,
                            // 'data_source'         => $data_source,
                            'fintech_provider'    => $fintech_provider,
                            'rsbsa_no'            => $rsbsa_no,
                            'first_name'          => $first_name,
                            'middle_name'         => $middle_name,
                            'last_name'           => $last_name,
                            'ext_name'            => $ext_name,
                            'id_number'           => $id_number,
                            'gov_id_type'         => $gov_id,
                            'street_purok'        => $street_purok,
                            'barangay'            => $barangay,
                            'municipality'        => $municipality,
                            'district'            => $district,                            
                            'province'            => $province,                            
                            'region'              => $region,
                            'birthdate'           => $birthdate,
                            'place_of_birth'      => $place_of_birth,
                            'mobile_no'           => $mobile_no,
                            'sex'                 => $sex,
                            'nationality'         => $nationality,
                            'profession'          => $profession,
                            'sourceoffunds'       => $sourceoffunds,
                            'mothers_maiden_name' => $mothers_maiden_name,
                            'no_parcel'           => $no_parcel,
                            'total_farm_area'     => $total_farm_area,
                            'account_number'      => $account,
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
        $this->region = $region_for_mail;
        $this->region_code = $region_code;
        
        // update total inserted in kyc file table
        if($rows_inserted != 0 ) {
            $get_total_inserted = db::table('kyc_files')->where('kyc_file_id',$get_kyc_file_id)->orderBy('date_uploaded','DESC')->first()->total_inserted;
            db::table('kyc_files')->where('kyc_file_id',$get_kyc_file_id)->update(["total_inserted" => $get_total_inserted + $rows_inserted]);
        }

        
        $upload_path = 'kyc_error';
  


        if(!File::isDirectory('temp_excel/'.$upload_path)){            
            File::makeDirectory('temp_excel/'.$upload_path, 0775, true);                                

        }


        // ERR
        $serialize_data = response()->json($error_data)->getContent();
        $clean_file_name = explode('.',$file_name)[0];
        $error_log_name = pathinfo('temp_excel/'.$upload_path.'/'.$clean_file_name, PATHINFO_FILENAME);    
        
        Storage::disk('temp_excel')->put($upload_path.'/'.$clean_file_name.'-error.json',$serialize_data);
        
        


        
    
        



        $role = "ICTS DMD";    
        $region = $region_for_mail;
        $message = "You have new ".$rows_inserted." records to approve.";

        // send email to rfo program focals.
        // if($rows_inserted != 0){
        //     $global_notif_model = new GlobalNotificationModel;
        //     $global_notif_model->send_email($role,$region,$message,$agency_id,$program_id);
        // }

      

        
        
    }catch(\Exception $e){
        $this->message = json_encode($e->getMessage());
        // $this->message = 'false';
    }   
      
   
    
        
    }

    public function startRow():int
    {
        return 2;
    }
    
    public function getRowCount()
    {

        return ['total_rows_inserted' => $this->inserted_count , 'total_rows' => $this->total_rows,"message"=>$this->message,"error_data" => $this->error_data,'region'=>$this->region_code];
    }


    public function newResult()
    {
         
       
        return ['total_rows_inserted' => $this->inserted_count , 'total_rows' => $this->total_rows,"message"=>$this->message,"error_data" => $this->error_data,'region'=>$this->region_code];
    }
 
}
