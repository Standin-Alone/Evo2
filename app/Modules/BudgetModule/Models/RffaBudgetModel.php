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
    
        // $gg = ["42383225-3a4e-4e18-8cda-deed9a62775f","37b5fdab-6482-433c-af96-455402d5ef77"];
        // foreach($gg as $val){
        //     array_push($arr, $val);
        // }    
                                
        $data = [];

        foreach($program_ids as $val){
            array_push($data, $val);
        }

        $result = str_replace('[', '', str_replace(']','', json_encode($data)));
        $program_id = str_replace('"', "'", str_replace('"',"'", $result));

        // $query = DB::select("SELECT fs.fund_id, fs.amount, p.title, p.shortname, p.description, gr.region, SUM(fs.amount) as total_amount, 
        //                         (SELECT SUM(ee.amount) FROM excel_export as ee WHERE ee.program_id IN ($program_id) GROUP BY ee.program_id) as disbursement_amount 
        //                     FROM fund_source as fs 
        //                     LEFT JOIN programs as p ON p.program_id = fs.program_id
        //                     LEFT JOIN geo_region as gr ON fs.reg = gr.code_reg
        //                     WHERE 
        //                         -- if(condition, true statement, false statement)
        //                         if($region != 13, fs.reg = $region AND fs.program_id IN ($program_id), fs.program_id IN ($program_id)) 
        //                     GROUP BY fs.program_id, fs.reg
        //                     ");

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

        $query = DB::select("SELECT p.title, ee.amount
                            FROM `kyc_profiles` as kp 
                            LEFT JOIN excel_export as ee ON ee.kyc_id = kp.kyc_id
                            LEFT JOIN fund_source as fs ON fs.fund_id = ee.fund_id
                            LEFT JOIN programs as p ON p.program_id = fs.program_id
                            WHERE ee.fund_id = '$fund_id' AND kp.kyc_id = ee.kyc_id");

        return $query;
    }
}
