<?php

namespace App\Modules\ReportModule\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ReportModule extends Model
{
    use HasFactory;

    // Total claim by region province, and supplier
    public function get_vt_active_payout($program_ids){
        $region_code = session()->get('region');

        $query = DB::table('voucher_transaction as vt')
                        ->select('vt.voucher_details_id','vt.reference_no', 's.supplier_name', 'p.description',
                                'v.reg', 'v.prv', 'g.reg_name', 'g.prov_name','vt.quantity', 'v.amount', 'vt.total_amount', 'vt.payout', 'vt.payout_date')
                        ->leftJoin('voucher as v', 'vt.reference_no', '=', 'v.reference_no')
                        ->leftJoin('supplier as s', 'vt.supplier_id', '=', 's.supplier_id')
                        ->leftJoin('supplier_programs as sp', 'vt.sub_program_id', '=', 'sp.sub_id')
                        ->leftJoin('programs as p', 'sp.program_id', '=', 'p.program_id')
                        // ->leftJoin('program_items as pi', 'sp.item_id', '=', 'pi.item_id')
                        ->leftJoin('geo_map as g', 'g.geo_code', '=', 'v.geo_code')
                        ->when($region_code, function ($query, $region_code) use($program_ids){
                            if($region_code != 13){
                                $query->where('v.reg','=', $region_code)
                                      ->whereIn('p.program_id', $program_ids)
                                      ->where('vt.payout', '=', "1")->groupBy('p.program_id', 'vt.reference_no');
                            }else{
                                $query->whereIn('p.program_id', $program_ids)->where('vt.payout', '=', '1')->groupBy('p.program_id', 'vt.reference_no');
                            }
                        })
                        ->get();

        return $query;
    }

    // Dashboard Cards
    public function total_amount_computation_active($program_ids){
        $region = session()->get('region');

        $data = [];

        foreach($program_ids as $val){
            array_push($data, $val);
        }

        $result = str_replace('[', '', str_replace(']','', json_encode($data)));
        $program_id = str_replace('"', "'", str_replace('"',"'", $result));

        // $query = DB::table('voucher_transaction as vt')
        //                 ->select('p.program_id','p.description', DB::raw('SUM(vt.total_amount) as program_total_amount'))
        //                 ->leftJoin('voucher as v', 'vt.reference_no', '=', 'v.reference_no')
        //                 ->leftJoin('supplier_programs as sp', 'vt.sub_program_id', '=', 'sp.sub_id')
        //                 ->leftJoin('programs as p', 'sp.program_id', '=', 'p.program_id')
        //                 ->leftJoin('geo_map as g', 'g.geo_code', '=', 'v.geo_code')
        //                 ->when($region, function($query, $region) use($program_ids, $program_description){
        //                     if($region != 13){
        //                         $query->where('v.reg', '=', $region)
        //                               ->whereIn('sp.program_id', $program_ids)
        //                               ->where('vt.payout', '=', "1")->groupBy('p.description');
        //                     }else{
        //                         $query->whereIn('sp.program_id', $program_ids)->where('vt.payout', '=', "1")->groupBy('p.description');
        //                     }
        //                 })
        //                 ->get();

        $query = DB::select("SELECT p.program_id, p.description, SUM(vt.total_amount) AS program_total_amount FROM voucher_transaction AS vt
                             LEFT JOIN voucher AS v ON vt.reference_no = v.reference_no
                             LEFT JOIN supplier_programs AS sp ON vt.sub_program_id = sp.sub_id
                             LEFT JOIN programs AS p ON sp.program_id =  p.program_id
                             LEFT JOIN geo_map AS g ON g.geo_code = v.geo_code
                             WHERE if($region != 13, v.reg = $region AND sp.program_id IN ($program_id),(CASE WHEN(sp.program_id IN ($program_id)) THEN 1 ELSE 0 END)) AND vt.payout = '1' GROUP BY p.description");

        return $query;
    }

    public function get_all_voucher_transaction_grand_total($program_ids){
        $region = session()->get('region');
        // $program_ids = session()->get('programs_ids');

        $query = DB::table('voucher_transaction as vt')
                        ->select(DB::raw('SUM(vt.total_amount) as grand_total'))
                        ->leftJoin('voucher as v', 'vt.reference_no', '=', 'v.reference_no')
                        ->leftJoin('supplier_programs as sp', 'vt.sub_program_id', '=', 'sp.sub_id')
                        ->leftJoin('programs as p', 'sp.program_id', '=', 'p.program_id')
                        ->leftJoin('geo_map as g', 'g.geo_code', '=', 'v.geo_code')
                        ->when($region, function($query, $region) use($program_ids){
                            if($region != 13){
                                $query->where('v.reg', '=', $region)
                                      ->whereIn('sp.program_id', $program_ids)
                                      ->where('vt.payout', '=', "1");
                                      
                            }else{
                                $query->whereIn('sp.program_id', $program_ids)->where('vt.payout', '=', '1');
                            }
                        })
                        ->get();

        return $query;
    }

    // claimed but not yet paid
    public function get_vt_inactive_payout($program_ids){
        $region_code = session()->get('region');

        $query = DB::table('voucher_transaction as vt')
                        ->select('vt.voucher_details_id','vt.reference_no', 's.supplier_name', 'p.description',
                                'v.reg', 'v.prv', 'g.reg_name', 'g.prov_name','vt.quantity', 'vt.amount', 'vt.total_amount', 'vt.payout', 'vt.payout_date')
                        ->leftJoin('voucher as v', 'vt.reference_no', '=', 'v.reference_no')
                        ->leftJoin('supplier as s', 'vt.supplier_id', '=', 's.supplier_id')
                        ->leftJoin('supplier_programs as sp', 'vt.sub_program_id', '=', 'sp.sub_id')
                        ->leftJoin('programs as p', 'sp.program_id', '=', 'p.program_id')
                        ->leftJoin('program_items as pi', 'sp.item_id', '=', 'pi.item_id')
                        ->leftJoin('geo_map as g', 'g.geo_code', '=', 'v.geo_code')
                        ->when($region_code, function ($query, $region_code) use($program_ids){
                            if($region_code != 13){
                                $query->where('v.reg','=', $region_code)
                                      ->whereIn('sp.program_id', $program_ids)
                                      ->where('vt.payout', '=', "0")->groupBy('vt.reference_no');
                            }else{
                                $query->whereIn('p.program_id', $program_ids)->where('vt.payout', '0')->groupBy('p.program_id', 'vt.reference_no');
                            }
                        })->get();

        return $query;
    }

    // Dashboard Cards
    public function total_amount_computation_inactive($program_ids){
        $region = session()->get('region');
        $program_description = session()->get('program_description_sets');

        $data = [];

        foreach($program_ids as $val){
            array_push($data, $val);
        }

        $result = str_replace('[', '', str_replace(']','', json_encode($data)));
        $program_id = str_replace('"', "'", str_replace('"',"'", $result));

        $query = DB::select("SELECT p.program_id, p.description, SUM(vt.total_amount) AS program_total_amount FROM voucher_transaction AS vt
                             LEFT JOIN voucher AS v ON vt.reference_no = v.reference_no
                             LEFT JOIN supplier_programs AS sp ON vt.sub_program_id = sp.sub_id
                             LEFT JOIN programs AS p ON sp.program_id =  p.program_id
                             LEFT JOIN geo_map AS g ON g.geo_code = v.geo_code
                             WHERE if($region != 13, v.reg = $region AND sp.program_id IN ($program_id),(CASE WHEN(sp.program_id IN ($program_id)) THEN 1 ELSE 0 END)) AND vt.payout = '0' GROUP BY p.description");

        return $query;
    }

    public function show_program_title_on_zero_value($program_ids){
        $region = session()->get('region');

        $query = DB::table('voucher_transaction as vt')
                        ->select('p.description')
                        ->leftJoin('voucher as v', 'vt.reference_no', '=', 'v.reference_no')
                        ->leftJoin('supplier_programs as sp', 'vt.sub_program_id', '=', 'sp.sub_id')
                        ->leftJoin('programs as p', 'sp.program_id', '=', 'p.program_id')
                        ->leftJoin('geo_map as g', 'g.geo_code', '=', 'v.geo_code')
                        ->when($region, function($query, $region) use($program_ids){
                            if($region != 13){
                                $query->where('v.reg', '=', $region)
                                    ->whereIn('p.program_id', $program_ids)
                                    ->groupBy('p.description');
                            }else{
                                $query->whereIn('p.program_id', $program_ids)->groupBy('p.description');
                            }
                        })
                        ->get();

        return $query;
    }

    public function get_all_inactive_voucher_transaction_grand_total($program_ids){
        $region = session()->get('region');
        
        $query = DB::table('voucher_transaction as vt')
                        ->select(DB::raw('SUM(vt.total_amount) as grand_total'))
                        ->leftJoin('voucher as v', 'vt.reference_no', '=', 'v.reference_no')
                        ->leftJoin('supplier_programs as sp', 'vt.sub_program_id', '=', 'sp.sub_id')
                        ->leftJoin('programs as p', 'sp.program_id', '=', 'p.program_id')
                        ->leftJoin('geo_map as g', 'g.geo_code', '=', 'v.geo_code')
                        ->when($region, function($query, $region) use($program_ids){
                            if($region != 13){
                                $query->where('v.reg', '=', $region)
                                      ->whereIn('p.program_id', $program_ids)
                                      ->where('vt.payout', '=', "0");
                                      
                            }else{
                                $query->whereIn('p.program_id', $program_ids)->where('vt.payout', '=', "0");
                            }
                        })
                        ->get();

        return $query;
    }

    /**
     * Total number of ready vouchers by region, province, and suppliers
     * 1.) voucher(remaining vouchers) NOT EXISTS voucher transcation(claimed vouchers)
     */
    public function total_ready_vouchers(){
        $region_code = session()->get('region');

        $query = DB::table('voucher as v')
                ->select('v.reference_no', 'v.reg', 'v.prv', 'v.amount', 'g.reg_name', 'g.prov_name')
                ->leftJoin('voucher_transaction as vt', 'v.reference_no', '=', 'vt.reference_no')
                ->leftJoin('supplier as s', 'vt.supplier_id', '=', 's.supplier_id')
                ->leftJoin('supplier_programs as sp', 'vt.sub_program_id', '=', 'sp.sub_id')
                ->leftJoin('geo_map as g', 'g.geo_code', '=', 'v.geo_code')
                ->when($region_code, function($query, $region_code){ 
                    if($region_code != 13){
                        $query->where('v.reg','=', $region_code)
                              ->whereNull('vt.reference_no');
                    }else{
                        $query->whereNull('vt.reference_no');
                    }
                })
                ->get();
        
        return $query;
    }

    public function ready_voucher_grand_total(){
        $region_code = session()->get('region');

        $query = DB::table('voucher as v')
                        ->select(DB::raw('SUM(v.amount) as grand_total'))
                        ->leftJoin('voucher_transaction as vt', 'v.reference_no', '=', 'vt.reference_no')
                        ->leftJoin('supplier as s', 'vt.supplier_id', '=', 's.supplier_id')
                        ->leftJoin('supplier_programs as sp', 'vt.sub_program_id', '=', 'sp.sub_id')
                        ->leftJoin('geo_map as g', 'g.geo_code', '=', 'v.geo_code')
                        ->when($region_code, function($query, $region_code){
                            if($region_code != 13){
                                $query->where('v.reg','=', $region_code)
                                      ->whereNull('vt.reference_no');
                            }else{
                                $query->whereNull('vt.reference_no')->groupBy('vt.reference_no');
                            }
                        })
                        ->get();

        return $query;
    }

    public function get_summary_claims($program_ids){
        $region_code = session()->get('region');

        // $program = session()->get('program_description');

        $query = DB::table('voucher_transaction as vt')
                        ->select('vt.voucher_details_id','vt.reference_no', 's.supplier_name', 
                                'v.reg', 'v.prv', 'g.reg_name', 'g.prov_name', 'p.description', 'pi.item_name','vt.quantity', 'vt.amount', 
                                'vt.total_amount', 'vt.transac_date', 'vt.transac_by_fullname','vt.payout', 'vt.payout_date')
                        ->leftJoin('voucher as v', 'vt.reference_no', '=', 'v.reference_no')
                        ->leftJoin('supplier as s', 'vt.supplier_id', '=', 's.supplier_id')
                        ->leftJoin('supplier_programs as sp', 'vt.sub_program_id', '=', 'sp.sub_id')
                        ->leftJoin('programs as p', 'sp.program_id', '=', 'p.program_id')
                        ->leftJoin('program_items as pi', 'sp.item_id', '=', 'pi.item_id')
                        ->leftJoin('geo_map as g', 'g.geo_code', '=', 'v.geo_code')
                        ->when($region_code, function ($query, $region_code) use($program_ids){
                            if($region_code != 13){
                                $query->where('v.reg','=', $region_code)
                                      ->whereIn('p.program_id', $program_ids);
                            }else{
                                $query->whereIn('p.program_id', $program_ids)->groupBy('p.program_id', 'vt.reference_no');
                            }
                        })->get();

        return $query;
    }

    public function get_summary_claims_grand_total($program_ids){
        $region = session()->get('region');

        // $program = session()->get('program_description');

        $query = DB::table('voucher_transaction as vt')
                        ->select(DB::raw('SUM(vt.total_amount) as grand_total'))
                        ->leftJoin('voucher as v', 'vt.reference_no', '=', 'v.reference_no')
                        ->leftJoin('supplier as s', 'vt.supplier_id', '=', 's.supplier_id')
                        ->leftJoin('supplier_programs as sp', 'vt.sub_program_id', '=', 'sp.sub_id')
                        ->leftJoin('programs as p', 'sp.program_id', '=', 'p.program_id')
                        ->leftJoin('geo_map as g', 'g.geo_code', '=', 'v.geo_code')
                        ->when($region, function($query, $region) use($program_ids){
                            if($region != 13){
                                $query->where('v.reg', '=', $region)
                                      ->whereIn('p.program_id', $program_ids);
                                    //   ->where('p.description', '=', $program);
                            }else{
                                $query->whereIn('p.program_id', $program_ids);
                            }
                        })->get();

        return $query;
    }

    /**
        *  if(there's as region_id selected){
        *      show = $query->sortBy('prov_name')->where('reg_code', '=', region_id)->unique(); // this result shows only selected region on province
        *  }
        *  if(there's no region_id selected) {
        *      show = $query->sortBy('prov_name')->unique();  // this result shows all province
        *  }
    */
    public function get_region_and_province(){
        $region_code = session()->get('region');

        $query = DB::table('geo_map')->get();

        $region = $query->SortBy('reg_name')->where('reg_code', '=', $region_code)->pluck('reg_name')->unique();

        $province = $query->SortBy('prov_name')->where('reg_code', '=', $region_code)->pluck('prov_name')->unique();

        return ['region' => $region, 'province'=> $province];
    }

    public function get_program($program_ids){
        $region = session()->get('region');

        $query = DB::table('program_permissions as pp')
                        ->select('pp.program_id' ,'p.description') 
                        ->leftJoin('programs as p', 'pp.program_id', '=', 'p.program_id')
                        ->leftJoin('users as u', 'pp.user_id', '=', 'u.user_id')
                        ->leftJoin('geo_map as g', 'u.geo_code', '=', 'g.geo_code')
                        ->when($region, function($query, $region) use($program_ids){
                            if($region != 13){
                                $query->where('u.reg', '=', $region)
                                      ->whereIn('pp.program_id', $program_ids)
                                      ->groupBy('pp.program_id');
                            }
                            else{
                                $query->whereIn('pp.program_id', $program_ids)->groupBy('p.program_id');
                            } 
                        })
                        ->get();

        return $query;
    }

    public function get_supplier(){
        $query = DB::table('supplier')->get();

        $supplier = $query->sortBy('supplier_name')->pluck('supplier_name')->unique();

        return ['supplier' => $supplier];
    }

    // Get All Region
    public function region(){
        $region_code = session()->get('region');

        $query = DB::table('geo_map')
                        ->select('reg_code', 'reg_name')
                        ->when($region_code, function($query, $region_code){
                            if($region_code != 13){
                                $query->where('reg_code', '=', $region_code)->groupBy('reg_code')->orderBy('reg_code');
                            }else{
                                $query->groupBy('reg_code')->orderBy('reg_code');
                            }
                        })
                        ->get();

        return $query;
    }

    // Get All Province
    public function province(){
        $region_code = session()->get('region');
        
        $query = DB::table('geo_map')
                        ->select('prov_code', 'prov_name')
                        ->when($region_code, function($query, $region_code){
                            if($region_code != 13){
                                $query->where('reg_name', '=', $region_code)->groupBy('prov_name');
                            }else{
                                $query->groupBy('prov_name');
                            }
                        })
                        ->get();

        return $query;
    }

    // Get All Municipality
    public function municipality(){
        $region_code = session()->get('region');
        $province_code = session()->get('province');

        $query = DB::table('geo_map')
                        ->select('mun_code', 'mun_name')
                        ->when($region_code, function($query, $region_code) use($province_code){
                            if($region_code != 13){
                                $query->where('reg_code', '=', $region_code)
                                      ->where('prov_code', '=', $province_code)
                                      ->groupBy('mun_name');
                            }else{
                                $query->groupBy('mun_name');
                            }
                        })
                        ->get();

        return $query;
    }

    // Get Region by Selected Province
    public function get_filter_region($prov_name){
        $region_code = session()->get('region');

        $province_name = explode(',', $prov_name);
        
        $query = DB::table('geo_map')
                        ->select('reg_code', 'reg_name')
                        ->when($prov_name, function($query, $prov_name) use($region_code, $province_name){
                            if($region_code != 13){ 
                                $query->where('prov_name', '=', $prov_name)->groupBy('reg_name')->orderBy('reg_code');
                            }else{
                                $query->whereIn('prov_name', $province_name)->groupBy('reg_name')->orderBy('reg_code');
                            }
                        })
                        ->get();

        return $query;
    }

    // Get Province by Selected Region
    public function get_filter_province($reg_name){
        $region_code = session()->get('region');

        $region_name = explode(',', $reg_name);

        $query = DB::table('geo_map')
                        ->select('prov_code', 'prov_name')
                        ->when($reg_name, function($query, $reg_name) use($region_code, $region_name){
                            if($region_code != 13){
                                $query->where('reg_name','=', $region_name)->groupBy('prov_name')->orderBy('reg_code');
                            }else{
                                $query->whereIn('reg_name', $region_name)->groupBy('prov_name')->orderBy('reg_code');
                            }
                        })
                        ->get();

        return $query;
    }

    // Get Municipalities by Selected Province
    public function get_filter_municipality($reg_name, $prov_name){
        $region_code = session()->get('region');

        $region_name = explode(',', $reg_name);

        $province_name = explode(',', $prov_name);

        $query = DB::table('geo_map')
                        ->select('mun_code', 'mun_name')
                        ->when($reg_name, function($query, $reg_name) use($region_code, $region_name, $province_name){
                            if($region_code != 13){
                                $query->where('reg_name','=', $region_name)->where('prov_name','=', $prov_name)->groupBy('mun_name')->orderBy('mun_code');
                            }else{
                                $query->whereIn('reg_name', $region_name)->whereIn('prov_name', $province_name)->groupBy('mun_name')->orderBy('mun_code');
                            }
                        })
                        ->get();

        return $query;
    }

    // Total number of vouchers
    public function total_number_of_vouchers(){
        $region_code = session()->get('region');
    
        $query = DB::table('voucher')
                        ->select(DB::raw('COUNT(*) AS total_number_of_vouchers'))
                        ->when($region_code, function($query, $region_code){
                            if($region_code != 13){
                                 $query->where('reg', '=', $region_code);
                            }
                        })
                        ->get();
        return $query;
    }

    public function ready_voucher_dashboard_computation(){
        $region_code = session()->get('region');

        $query = DB::select("SELECT k.total_no_of_vouchers, k.total_no_of_used_vouchers, (k.total_no_of_vouchers - k.total_no_of_used_vouchers) AS total_no_of_unused_vouchers
                                FROM (
                                        SELECT count(n.v_ref_no) AS total_no_of_vouchers, count(n.vt_ref_no) total_no_of_used_vouchers
                                        FROM(
                                                SELECT v.reference_no AS v_ref_no, vt.reference_no AS vt_ref_no FROM voucher AS v
                                                LEFT JOIN 
                                                (
                                                    SELECT * FROM voucher_transaction
                                                    GROUP BY voucher_transaction.reference_no
                                                ) AS vt ON vt.reference_no = v.reference_no
                                            ) AS n 
                                ) AS k");

        return $query;
    }
    
        public function total_number_of_paid_voucher_transactions(){
            $region_code = session()->get('region');

            $query = DB::select("SELECT k.total_no_of_used_vouchers
                                    FROM (
                                            SELECT count(n.v_ref_no) AS total_no_of_vouchers, count(n.vt_ref_no) total_no_of_used_vouchers
                                            FROM(
                                                    SELECT v.reference_no AS v_ref_no, vt.reference_no AS vt_ref_no FROM voucher as v
                                                    LEFT JOIN 
                                                    (
                                                        SELECT * FROM voucher_transaction
                                                        where voucher_transaction.payout = '1'
                                                        
                                                    ) AS vt ON vt.reference_no = v.reference_no group by vt.reference_no
                                                ) AS n  
                                    ) AS k  ");

            return $query;
    
            // $query = DB::table('voucher_transaction as vt')
            //                 ->select(DB::raw('COUNT(vt.reference_no) as total_no_of_paid'))
            //                 ->leftJoin('voucher as v', 'v.reference_no', '=', 'vt.reference_no')
            //                 ->when($region_code, function($query, $region_code){
            //                     if($region_code != 13){
            //                         $query->where('v.reg', '=', $region_code)->where('vt.payout', '=', '1');
            //                     }else{
            //                         $query->where('vt.payout', '=', '1');
            //                     }
            //                 })
            //                 ->get();
    
            // return $query;
        }
    
        public function total_number_of_not_paid_voucher_transactions(){
            $region_code = session()->get('region');

            $query = DB::select("SELECT k.total_no_of_used_vouchers
                                    FROM (
                                            SELECT count(n.v_ref_no) AS total_no_of_vouchers, count(n.vt_ref_no) total_no_of_used_vouchers
                                            FROM(
                                                    SELECT v.reference_no AS v_ref_no, vt.reference_no AS vt_ref_no FROM voucher as v
                                                    LEFT JOIN 
                                                    (
                                                        SELECT * FROM voucher_transaction
                                                        WHERE voucher_transaction.payout = '0'
                                                        
                                                    ) AS vt ON vt.reference_no = v.reference_no GROUP BY vt.reference_no
                                                ) AS n 
                                    ) AS k  ");

            // $query = DB::table('voucher_transaction as vt')
            //                 ->select(DB::raw('COUNT(vt.reference_no) as total_no_of_not_paid'))
            //                 ->leftJoin('voucher as v', 'v.reference_no', '=', 'vt.reference_no')
            //                 ->when($region_code, function($query, $region_code){
            //                     if($region_code != 13){
            //                         $query->where('v.reg', '=', $region_code)->where('vt.payout', '=', '0');
            //                     }else{
            //                         $query->where('vt.payout', '=', '0');
            //                     }
            //                 })
            //                 ->get();
    
            return $query;
        }
    
}
