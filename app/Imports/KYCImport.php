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
    /**
    * @param Collection $collection
    */
    public function collection(Collection $collection)
    {
        //  
        try{
        $rows_inserted = 0;
        
        foreach($collection as $item){
            
            $rsbsa_no   = $item[0];
            
            // check rsbsa no if exists
            $check_rsbsa_no = db::table('kyc_profiles')->where('rsbsa_no',trim($rsbsa_no))->get();


            if($check_rsbsa_no->isEmpty()){                
             
                // insert to kyc profiles
            db::transaction(function() use ($item,&$rows_inserted){
                   

                    $format_birthday = \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($item[13])->format('Y-m-d');

                    $uuid                = Uuid::uuid4();
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
                    $remarks             = $item[23] == '' ? 'failed' : trim($item[23]);

                    $check_reg_prov =  db::table('geo_map')->where('prov_name',$province)->where('reg_name',$region)->get();    

                    if(!$check_reg_prov->isEmpty()){
                    

                       $insert_kyc = db::table('kyc_profiles')
                            ->insert([
                                'kyc_id'              => $uuid,
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
                                'remarks'             => $remarks
                            ]);
                   
                        if($insert_kyc){
                            ++$rows_inserted;
                        }
                    }

                    
                });             
            }            
        }
        
        
        $this->inserted_count = $rows_inserted;   
        $this->total_rows = $collection->count();
        $this->message = 'true';
    }catch(\Exception $e){
        $this->message = 'false';
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
