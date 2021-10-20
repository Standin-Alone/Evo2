<?php

namespace App\Exports;

use Carbon\Carbon;
use Ramsey\Uuid\Uuid;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Crypt;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithMapping;
use illuminate\Contracts\Support\Renderable;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Contracts\Support\Responsable;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use Illuminate\Contracts\Encryption\DecryptException;

class GenerateBeneficiariesExcel implements FromCollection, ShouldAutoSize
{
    use Exportable;
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        $row_value = [];
        $session_reg_code = sprintf("%02d", session('reg_code'));
        $session_selected_prv_code = session('selected_prov_code');
        $getData = DB::table('kyc_profiles as kyc')
                        ->select(DB::raw("kyc.kyc_id,kyc.dbp_batch_id,kyc.data_source,kyc.fintech_provider,kyc.rsbsa_no,kyc.first_name,kyc.middle_name,kyc.last_name,kyc.ext_name,
                        kyc.id_number,kyc.gov_id_type,kyc.street_purok,kyc.bgy_code,kyc.barangay,kyc.mun_code,kyc.municipality,kyc.district,kyc.prov_code,kyc.province,
                        kyc.reg_code,kyc.region,kyc.birthdate,kyc.place_of_birth,kyc.mobile_no,kyc.sex,kyc.nationality,kyc.profession,kyc.sourceoffunds,kyc.mothers_maiden_name,
                        kyc.no_parcel,kyc.total_farm_area,kyc.account_number,kyc.remarks,kyc.uploaded_by_user_id,kyc.uploaded_by_user_fullname,kyc.date_uploaded,".session("disburse_amount")." as amount"))
                        ->where('kyc.reg_code',$session_reg_code)   
                        ->where('kyc.prov_code',$session_selected_prv_code) 
                        ->where('kyc.isremove','0') 
                        ->where(function ($query){
                            if(session('role_id') == 8){
                            $query->where('kyc.isapproved','1')
                            ->where('kyc.approved_by_b','0');
                            }
                            else if(session('role_id') == 10){
                            $query->where('kyc.isapproved','1')
                            ->where('kyc.approved_by_b','1')
                            ->where('kyc.approved_by_d','0');
                            }
                            else if(session('role_id') == 4){
                            $query->where('kyc.isapproved','0');
                            }else{
                                return dd("Access Denied!");
                            }
                        })
                    ->get();
        
            foreach ($getData as $key => $row) {                
                $row_value[] = [$row->date_uploaded,$row->data_source,$row->fintech_provider,$row->rsbsa_no,$row->first_name,$row->middle_name,$row->last_name,$row->ext_name,$row->id_number,$row->gov_id_type,
                $row->street_purok,$row->bgy_code,$row->barangay,$row->mun_code,$row->municipality,$row->district,$row->prov_code,$row->province,$row->reg_code,$row->region,$row->birthdate,
                $row->place_of_birth,$row->mobile_no,$row->sex,$row->nationality,$row->profession,$row->sourceoffunds,$row->mothers_maiden_name,$row->no_parcel,$row->total_farm_area,
                $row->account_number,$row->remarks,$row->amount];
            } 

        return new Collection([
            
            // COLUMN NAMES
            ['DATE UPLOADED','DATA SOURCE','FINTECH PROVIDER','RSBSA NO.','FIRST NAME','MIDDLE NAME','LAST NAME','EXTENTION NAME','ID NUMBER','GOVERNMENT ID TYPE','STREET-PUROK',
            'BARANGAY CODE','BARANGAY NAME','MUNICIPALITY CODE','MUNICIPALITY NAME','DISTRICT','PROVINCE CODE','PROVINCE NAME','REGION CODE','REGION NAME','BIRTHDATE',
            'PLACE OF BIRTH','MOBILE NO.','SEX','NATIONALITY','PROFESSION','SOURCE OF FUNDS','MOTHERS MAIDEN NAME','NO. PARCEL','TOTAL FARM AREA','ACCOUNT NUMBER','REMARKS',
            'TOTAL AMOUNT'],
            
            // ROW DETAILS
            $row_value
        ]);  

    }

}


