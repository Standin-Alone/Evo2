<?php

namespace App\Modules\BudgetModule\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class RffaBudgetModel extends Model
{
    use HasFactory;

    // Disbursement for RFFA
    public function rffa_disbursement($program_ids){
        $region = session()->get('region');
                                
        $data = [];

        foreach($program_ids as $val){
            array_push($data, $val);
        }

        $result = str_replace('[', '', str_replace(']','', json_encode($data)));
        $program_id = str_replace('"', "'", str_replace('"',"'", $result));

        // get the Fund ID 
        $fund_query = DB::table('fund_source as fs')
                ->select('fs.fund_id')
                ->when($region, function($fund_query, $region) use($program_ids){
                    if($region != 13){
                        $fund_query
                                    ->where('fs.reg', '=', $region)
                                    ->whereIn('fs.program_id', $program_ids)
                                    ->groupBy('fs.program_id', 'fs.reg');
                    }else{  
                        $fund_query
                                ->whereIn('fs.program_id', $program_ids)
                                ->groupBy('fs.program_id', 'fs.reg');
                    }
                })
                ->get();

        $id = [];

        foreach($fund_query as $val){
            array_push($id, $val->fund_id);
        }

        $result = str_replace('[', '', str_replace(']','', json_encode($id)));

        $fund_id = str_replace('"', "'", str_replace('"',"'", $result));

        $query = DB::select("SELECT gr.region, p.title, fs.fund_id, kp.kyc_id, ee.kyc_id, fs.amount as total_amount, SUM(ee.amount) as disbursement_amount
                            FROM `kyc_profiles` as kp 
                            LEFT JOIN excel_export as ee ON ee.kyc_id = kp.kyc_id
                            LEFT JOIN fund_source as fs ON fs.fund_id = ee.fund_id
                            LEFT JOIN programs as p ON p.program_id = fs.program_id
                            LEFT JOIN geo_region as gr ON fs.reg = gr.code_reg
                            WHERE ee.fund_id IN ($fund_id) AND kp.kyc_id = ee.kyc_id GROUP BY ee.fund_id, fs.reg");

        return $query;
    }

    public function get_rffa_fund_source_breakdown($fund_id){
        $region = session()->get('region');

        $query = DB::select("SELECT ee.rsbsa_no, ee.first_name, ee.middle_name, ee.last_name, AES_DECRYPT(kp.account_number,'".session('private_secret_key')."') as account_number, p.title, ee.amount
                            FROM `kyc_profiles` as kp 
                            LEFT JOIN excel_export as ee ON ee.kyc_id = kp.kyc_id
                            LEFT JOIN fund_source as fs ON fs.fund_id = ee.fund_id
                            LEFT JOIN programs as p ON p.program_id = fs.program_id
                            WHERE ee.fund_id = '$fund_id' AND kp.kyc_id = ee.kyc_id AND kp.rsbsa_no = ee.rsbsa_no");

        return $query;
    }
}
