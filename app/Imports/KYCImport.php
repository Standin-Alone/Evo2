<?php

namespace App\Imports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithStartRow;
use DB;
use Ramsey\Uuid\Uuid;
use Carbon\Carbon;
class KYCImport implements ToCollection,WithStartRow
{

    private $inserted_count = 0;
    private $total_rows = 0;
    private $message = '';
    protected $provider;

    public function __construct($provider){
        $this->provider = $provider;    
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

        ini_set("memory_limit", "10056M");

        foreach($collection as $key => $item){
            
            // check rsbsa no if exists
            $rsbsa_no   = $item[1];                
            $check_rsbsa_no = db::table('kyc_profiles')->where('rsbsa_no',trim($rsbsa_no))->get();


            if($key != 400){

                if($check_rsbsa_no->isEmpty()){                
                 
                    // insert to kyc profiles
                db::transaction(function() use ($item,&$rows_inserted , $PRIVATE_KEY , $provider ){
                    

                        $format_birthday = \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($item[14])->format('Y-m-d');

                        $uuid                = Uuid::uuid4();
                        $data_source         = trim($item[0]);    
                        $fintech_provider    = $provider;    
                        $rsbsa_no            = trim($item[1]);    
                        $first_name          = trim($item[2]);            
                        $middle_name         = trim($item[3]);
                        $last_name           = trim($item[4]);
                        $ext_name            = trim($item[5]);
                        $id_number           = trim($item[6]);
                        $gov_id              = trim($item[7]);
                        $street_purok        = trim($item[8]);
                        $barangay            = trim($item[9]);
                        $municipality        = trim($item[10]);
                        $district            = trim($item[11]);                        
                        $province            = trim($item[12]);
                        $region              = trim($item[13]);
                        $birthdate           = $format_birthday;
                        $place_of_birth      = trim($item[15]);
                        $mobile_no           = trim($item[16]);
                        $sex                 = trim($item[17]);
                        $nationality         = trim($item[18]);
                        $profession          = trim($item[19]);
                        $sourceoffunds       = trim($item[20]);
                        $mothers_maiden_name = trim($item[21]);
                        $no_parcel           = trim($item[22]);
                        $total_farm_area     = trim($item[23]);
                        $account             = trim($item[24]);
                        $remarks             = is_null($item[24]) ? 'Failed' : trim($item[25]);

                        $check_reg_prov =  db::table('geo_map')->where('prov_name',$province)->where('reg_name',$region)->get();    

                        if(!$check_reg_prov->isEmpty()){
                        
                        $prov_code   =  db::table('geo_map')->where('prov_name',$province)->first()->prov_code;
                        $reg_code   =  db::table('geo_map')->where('reg_name',$region)->first()->reg_code;
                        $insert_kyc = db::table('kyc_profiles')
                                ->insert([
                                    'kyc_id'              => $uuid,
                                    'data_source'         => $data_source,
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
                                    'prov_code'           => $prov_code,
                                    'province'            => $province,
                                    'reg_code'            => $reg_code,
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
                                    'account_number'      => !is_null($item[24]) ? DB::raw("AES_ENCRYPT(".$account.",'".$PRIVATE_KEY."')") : '' ,
                                    'remarks'             => $remarks
                                ]);
                    
                            if($insert_kyc){
                                ++$rows_inserted;
                            }
                        }

                        
                    });             
                }     
            
            }
        }
        
        
        $this->inserted_count = $rows_inserted;   
        $this->total_rows = $collection->count();
        $this->message = 'true';
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

        return json_encode(['total_rows_inserted' => $this->inserted_count , 'total_rows' => $this->total_rows,"message"=>$this->message]);
    }
}