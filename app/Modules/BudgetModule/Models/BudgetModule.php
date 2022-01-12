<?php

namespace App\Modules\BudgetModule\Models;

use Ramsey\Uuid\Uuid;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class BudgetModule extends Model
{
    use HasFactory;

    protected $table = "fund_source";

    public function get_program($program_ids){
        $region = session()->get('region');

        $query = DB::table('program_permissions as pp')
                        ->select('pp.program_id' ,'p.title', 'p.shortname', 'p.description') 
                        ->leftJoin('programs as p', 'pp.program_id', '=', 'p.program_id')
                        ->leftJoin('users as u', 'pp.user_id', '=', 'u.user_id')
                        ->leftJoin('geo_map as g', 'u.geo_code', '=', 'g.geo_code')
                        ->when($region, function($query, $region) use($program_ids){
                            if($region != 13){
                                $query->where('u.reg', '=', $region)
                                      ->whereIn('pp.program_id', $program_ids)
                                      ->groupBy('p.program_id');
                            }
                            else{
                                $query->whereIn('pp.program_id', $program_ids)->groupBy('p.program_id');
                            } 
                        })
                        ->get();

        return $query;
    }
    
    public function insert_new_fund($program, $uacs, $gfi, $farmers, $amount, $region, $province, $particulars){
        $query = DB::table('fund_source')->insert([
                    'fund_id' => Uuid::uuid4(),
                    'program_id'=> $program, 
                    'uacs'=> $uacs, 
                    'gfi' => $gfi,
                    'no_of_farmers' => $farmers,
                    'amount'=> $amount, 
                    'reg'=> $region, 
                    'prv'=> $province, 
                    'particulars'=> $particulars,
                ]);

        return $query;
    }
    
    public function get_reg(){
        $region = session()->get('region');
        if($region != 13){
            $region = session()->get('region');

            $query = DB::select('SELECT reg_code, reg_name from geo_map where reg_code = '.$region.' GROUP BY reg_name HAVING COUNT(*) > 1');
        }else{
            $query = DB::select("CALL get_regions");
        }
        
        return $query;
    }

    public function get_prov($reg_code){
        $query = DB::select("CALL get_provinces(" . $reg_code . ")");

        return $query;
    }

    public function disbursement($program_ids){
        $region = session()->get('region');

        // $program_ids = session()->get('programs_ids');

        // $program_ids = DB::table('program_permissions as pp')
        //                     ->leftJoin('programs as p', 'pp.program_id', '=', 'p.program_id')
        //                     ->where('user_id', '=', $uuid)
        //                     ->pluck('pp.program_id')
        //                     ->all();

        DB::enableQueryLog();
        $query = DB::table('fund_source as fs')
                        ->select('fs.fund_id', 'fs.fund_name', 'fs.particulars', 'gr.region', 'p.title', 'p.description', 'fs.amount')
                        ->leftJoin('programs as p', 'fs.program_id', '=', 'p.program_id')
                        ->leftJoin('geo_region as gr', 'fs.reg', '=', 'gr.code_reg')

                        ->when($region, function($query, $region) use($program_ids){
                            if($region != 13){
                                $query->where('fs.reg', '=', $region)
                                      ->whereIn('fs.program_id', $program_ids);
                            }else{
                                $query->whereIn('fs.program_id', $program_ids);;
                            }
                        })
                        ->get();

        // print_r(DB::getQueryLog());

        return $query;
    }

    public function disbursement_v2($program_ids){
        $region = session()->get('region');

        $data = [];

        foreach($program_ids as $val){
            array_push($data, $val);
        }

        $result = str_replace('[', '', str_replace(']','', json_encode($data)));
        $program_id = str_replace('"', "'", str_replace('"',"'", $result));

        $query = DB::select(
                            "SELECT A.fund_id, A.prog_desc, A.program_id, A.title, A.region_name, A.total_amount, A.disbursement_amount, (A.total_amount) - (A.disbursement_amount) as remaining_amount
                             FROM (
                                    SELECT fs.fund_id as fund_id, p.program_id as program_id, p.title as title, p.description as prog_desc, fs.reg as region_code, gr.region as region_name, fs.amount as total_amount, SUM(vt.total_amount) as disbursement_amount, (fs.amount) - (vt.total_amount) as remaining_amount
                                    FROM voucher_transaction as vt
                                    LEFT JOIN fund_source as fs on fs.fund_id = vt.fund_id
                                    LEFT JOIN supplier_programs as sp on sp.sub_id = vt.sub_program_id
                                    LEFT JOIN programs as p on p.program_id = sp.program_id
                                    LEFT JOIN geo_region as gr on gr.code_reg = fs.reg
                                    WHERE if($region!= 13, fs.reg=$region AND p.program_id IN ($program_id), p.program_id IN ($program_id))
                                    group by fs.fund_id, p.program_id

                                    UNION ALL
                                    
                                    SELECT fs.fund_id as fund_id, p.program_id as program_id, p.title as title, p.description as prog_desc, fs.reg as region_code, gr.region as region_name, fs.amount as total_amount, SUM(ee.amount) as disbursement_amount, (fs.amount) - (ee.amount) as remaining_amount
                                    FROM kyc_profiles as kp 
                                    LEFT JOIN excel_export as ee ON ee.kyc_id = kp.kyc_id
                                    LEFT JOIN fund_source as fs ON fs.fund_id = ee.fund_id
                                    LEFT JOIN programs as p ON p.program_id = fs.program_id
                                    LEFT JOIN geo_region as gr ON fs.reg = gr.code_reg
                                    WHERE if($region != 13, fs.reg=$region AND kp.kyc_id = ee.kyc_id, kp.kyc_id = ee.kyc_id)
                                    group by fs.fund_id
                                ) AS A order by A.region_code, A.program_id");

        return $query;
    }

    public function breakdown($fund_id, $reg_program){
        $region = session()->get('region');

        $query = DB::table('voucher_transaction as vt')
                        ->select('fs.fund_name', 'vt.reference_no', 'v.first_name', 'v.middle_name', 'v.last_name', 'v.ext_name', 's.supplier_name', 'p.title', 'p.description', 
                        'pi.item_name', 'vt.quantity', 'vt.amount', 'vt.total_amount')
                        ->leftJoin('voucher as v', 'v.reference_no', '=', 'vt.reference_no')
                        ->leftJoin('supplier as s', 'vt.supplier_id', '=', 's.supplier_id')
                        ->leftJoin('supplier_programs as sp', 'vt.sub_program_id', '=', 'sp.sub_id')
                        ->leftJoin('programs as p', 'sp.program_id', '=', 'p.program_id')
                        ->leftJoin('program_items as pi', 'sp.item_id', '=', 'pi.item_id')
                        ->leftJoin('fund_source as fs', 'vt.fund_id', '=', 'fs.fund_id')
                        ->when($region, function($query, $region) use($fund_id, $reg_program){
                            if($region != 13){
                                $query->where('fs.reg', '=', $region)
                                      ->where('fs.fund_id', '=', $fund_id)
                                      ->where('p.description', '=', $reg_program);
                            }
                            else{
                                $query->where('fs.fund_id', '=', $fund_id)
                                      ->where('p.description', '=', $reg_program);
                            }
                            
                        })
                        ->get();

        return $query;
    }

    public function overview($program_ids){
        $region = session()->get('region');

        $query = DB::table('fund_source as fs')
                        ->select('fs.fund_id', 'p.title', 'p.description', 'fs.uacs', 'fs.gfi', 'fs.reg', 'gr.region', 
                        DB::raw('ifnull(fs.no_of_farmers, 0) as target_of_benefeciaries'), 'fs.amount', 'fs.particulars')
                        ->leftJoin('programs as p', 'fs.program_id', '=', 'p.program_id')
                        ->leftJoin('geo_region as gr', 'fs.reg', '=', 'gr.code_reg')
                        ->when($region, function($query, $region) use($program_ids){
                            if($region != 13){
                                $query->where('fs.reg', '=', $region)
                                      ->whereIn('fs.program_id', $program_ids);
                            }else{
                                $query->whereIn('fs.program_id', $program_ids);;
                            }
                        })
                        ->get();

        return $query;
    }

    public function update_overview_amount($fund_id, $uacs, $gfi, $farmers, $amount, $region, $particulars){      
        $query = DB::update('update fund_source set uacs = ?, gfi = ?, reg = ?, no_of_farmers = ?, amount = ?, particulars = ? where fund_id = ?', [$uacs, $gfi, $region, $farmers, $amount, $particulars, $fund_id]);

        return $query;
    }

    public $timestamps = false;
}