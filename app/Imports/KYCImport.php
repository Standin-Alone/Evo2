<?php

namespace App\Imports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithStartRow;
use DB;
use Ramsey\Uuid\Uuid;
use Carbon\Carbon;
use App\Models\GlobalNotificationModel;
class KYCImport implements ToCollection,WithStartRow
{

    private $inserted_count = 0;
    private $total_rows = 0;
    private $message = '';
    protected $provider;
    protected $file_name;

    private $error_data;
    private $region;
    
    public function __construct($provider, $file_name){
        $this->provider = $provider;   
        $this->file_name = $file_name;    
    
    }



    /**
    * @param Collection $collection
    */
    public function collection(Collection $collection)
    {
        //      

        $PRIVATE_KEY =  '3273357538782F413F4428472B4B6250655368566D5971337436773979244226452948404D635166546A576E5A7234753778214125442A462D4A614E64526755'.
                        '6A586E327235753778214125442A472D4B6150645367566B59703373367639792F423F4528482B4D6251655468576D5A7134743777217A25432646294A404E63'.
                        '5166546A576E5A7234753777217A25432A462D4A614E645267556B58703273357638792F413F4428472B4B6250655368566D597133743677397A244326452948'.
                        '2B4D6251655468576D5A7134743777397A24432646294A404E635266556A586E3272357538782F4125442A472D4B6150645367566B5970337336763979244226'.
                        '4428472B4B6250655368566D5971337436773979244226452948404D635166546A576E5A7234753778214125432A462D4A614E645267556B5870327335763879';

        try{
            
        $rows_inserted = 0;
        $provider = $this->provider;
        $file_name = $this->file_name;
        $collection_count = $collection->count();
        $region_for_mail = '';
        
        $error_data = [];
        ini_set("memory_limit", "10056M");
                   
        $get_kyc_file_id = '';
        foreach($collection as $key => $item){
            
            // check rsbsa no if exists
            $rsbsa_no   = $item[0];                
            $check_rsbsa_no = db::table('kyc_profiles')->where('rsbsa_no',trim($rsbsa_no))->take(1)->get();
            

            
           

                if($check_rsbsa_no->isEmpty()){                
                        // insert kyc files to database
                        $check_filename = db::table('kyc_files')->where('file_name',$file_name)->first();
                 
                        
                        if(!$check_filename){
                            $get_kyc_file_id = db::table('kyc_files')
                                ->insertGetId([
                                    "file_name" => $file_name,
                                    "total_rows" => $collection_count,
                                ]);
                        }else{
                            $get_kyc_file_id = $check_filename->kyc_file_id;
                        }

                    // insert to kyc profiles
                    db::transaction(function() use ($item,&$rows_inserted , $PRIVATE_KEY , $provider, &$error_data,&$region_for_mail,$file_name,$collection_count,$get_kyc_file_id){
                    
                        // $format_birthday = str_replace('/','-',$item[13]);

                        $format_birthday = strpos($item[13], '/') || is_int($item[13]) ? \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($item[13]) : $item[13];
                        //$format_birthday = \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($item[13])->format('Y-m-d');
                        

                        // this comment is for data with data source
                        // $data_source         = trim($item[0]);    
                        // $fintech_provider    = $provider;    
                        // $rsbsa_no            = trim($item[1]);    
                        // $first_name          = trim($item[2]);            
                        // $middle_name         = trim($item[3]);
                        // $last_name           = trim($item[4]);
                        // $ext_name            = trim($item[5]);
                        // $id_number           = trim($item[6]);
                        // $gov_id              = trim($item[7]);
                        // $street_purok        = trim($item[8]);
                        // $barangay            = trim($item[9]);
                        // $municipality        = trim($item[10]);
                        // $district            = trim($item[11]);                        
                        // $province            = trim($item[12]);
                        // $region              = trim($item[13]);
                        // $birthdate           = $format_birthday;
                        // $place_of_birth      = trim($item[15]);
                        // $mobile_no           = trim($item[16]);
                        // $sex                 = trim($item[17]);
                        // $nationality         = trim($item[18]);
                        // $profession          = trim($item[19]);
                        // $sourceoffunds       = trim($item[20]);
                        // $mothers_maiden_name = trim($item[21]);
                        // $no_parcel           = trim($item[22]);
                        // $total_farm_area     = trim($item[23]);
                        // $account             = trim($item[24]);
                        // $remarks             = is_null($item[24]) ? 'Failed' : trim($item[25]);



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
                                                ->where('prov_name',$province)
                                                ->where('reg_name',$region)
                                                ->where('bgy_name',$barangay)
                                                ->where('mun_name',$municipality)
                                                ->get(); 
                                                
                        $check_account_number = db::table('kyc_profiles')->where(DB::raw("AES_DECRYPT(account_number,'".$PRIVATE_KEY."')"),$account)->take(1)->get();

                        if(!$check_reg_prov->isEmpty() && !is_null($item[23]) && !is_null($first_name) && !is_null($last_name) && $check_account_number->isEmpty()){

                            // set region for send email
                            $region_for_mail =  $region; 

                            $bgy_code   =  db::table('geo_map')->where('bgy_name',$barangay)->first()->bgy_code;
                            $mun_code   =  db::table('geo_map')->where('mun_name',$municipality)->first()->mun_code;
                            $prov_code   =  db::table('geo_map')->where('prov_name',$province)->first()->prov_code;
                            $reg_code   =  db::table('geo_map')->where('reg_name',$region)->first()->reg_code;
                            
                            

               
                        
                        


                        $insert_kyc = db::table('kyc_profiles')
                                ->insert([
                                    'kyc_id'              => $uuid,
                                    'kyc_file_id'         => $get_kyc_file_id,
                                    // 'data_source'         => $data_source,
                                    'fintech_provider'    => $fintech_provider,
                                    'rsbsa_no'            => $rsbsa_no,
                                    'first_name'          => preg_replace('/[0-9]+/','',str_replace("Ñ","N", mb_strtoupper($first_name,'UTF-8'))),
                                    'middle_name'         => preg_replace('/[0-9]+/','',str_replace("Ñ","N", mb_strtoupper($middle_name == '' ? 'NMN' : $middle_name,'UTF-8')))  ,
                                    'last_name'           => preg_replace('/[0-9]+/','',str_replace("Ñ","N", mb_strtoupper($last_name,'UTF-8'))),
                                    'ext_name'            => $ext_name,
                                    'id_number'           => $id_number,
                                    'gov_id_type'         => $gov_id,
                                    'street_purok'        => str_replace("Ñ","N", mb_strtoupper($street_purok,'UTF-8')),
                                    'bgy_code'            => $bgy_code,
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
                                    'account_number'      => DB::raw("AES_ENCRYPT('".$account."','".$PRIVATE_KEY."')"),
                                    'remarks'             => mb_strtoupper($remarks),
                                    'uploaded_by_user_id' => session('uuid'),
                                    'uploaded_by_user_fullname'  => str_replace("Ñ","N", mb_strtoupper(session('first_name'),'UTF-8')).' '.str_replace("Ñ","N", mb_strtoupper(session('last_name'),'UTF-8'))
                                ]);
                    
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

                            if($first_name == '' && $last_name == '' ){
                                $error_remarks = ($error_remarks == ''  ? 'Incomplete name' : $error_remarks.','.'Incomplete name');
                            }


                            
                            if($check_reg_prov->isEmpty()){
                                $error_remarks = ($error_remarks == ''  ? 'Incomplete or wrong spelling of address' : $error_remarks.','.'Incomplete or wrong spelling of address');
                            }

                            if(!$check_account_number->isEmpty()){
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
                            ];
                                
                            array_push($error_data,$data);
                        }

                        
                    });             
                }     
            
            
        }
        
        $this->error_data = $error_data;
        $this->inserted_count = $rows_inserted;   
        $this->total_rows = $collection->count();
        $this->message = 'true';
        $this->region = $region_for_mail;
        
        // update total inserted in kyc file table
        if($rows_inserted != 0){
            $get_total_inserted = db::table('kyc_files')->where('kyc_file_id',$get_kyc_file_id)->first()->total_inserted;
            db::table('kyc_files')->where('kyc_file_id',$get_kyc_file_id)->update(["total_inserted" => $get_total_inserted + $rows_inserted]);
        }

        $role = "ICTS DMD";    
        $region = $region_for_mail;
        $message = "You have new ".$rows_inserted." records to approve.";

        // send email to rfo program focals.
        if($rows_inserted != 0){
            $gloal_notif_model = new GlobalNotificationModel;
            $gloal_notif_model->send_email($role,$region,$message);
        }
        

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

        return json_encode(['total_rows_inserted' => $this->inserted_count , 'total_rows' => $this->total_rows,"message"=>$this->message,"error_data" => $this->error_data,'region'=>$this->region]);
    }

 
}
