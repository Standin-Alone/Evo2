<?php

namespace App\Modules\SummaryMonitoring\Models;
use Session;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;
use DateTime;
use DatePeriod;
use DateInterval;

class SummaryMonitoring extends Model
{
    use HasFactory;

    public function get_funds_liquidation($date_start, $date_end){
        //date_default_timezone_set('Asia/Manila');

    	$reg_code 	 = Session::get('reg_code');
    	$program_ids = Session::get('program_id_sets');
        $today       = date('Y-m-d');

    	$funds = DB::table('vw_fund_source_summary')
    			   ->selectRaw('SUM(amount) as total_amount, SUM(total_liquidated) as total_liquidated,program_id, program_title, program_shortname')
    			   //->where('reg', $reg_code)
                   ->where(function($funds) use($reg_code) {
                        if($reg_code != 13){
                            $funds->where('reg', $reg_code);
                        }
                   })
                   ->whereIn('program_id', $program_ids)
    		       ->groupBy('program_id')
    		       ->get();

        $liquidated = DB::table('excel_export')
                        ->selectRaw('SUM(amount) as total_liquidated, program_id')
                        ->where(function($liquidated) use($reg_code) {
                            if($reg_code != 13){
                                $liquidated->where('reg_code', $reg_code);
                            }
                        })
                        ->where(function($liquidated) use($today, $date_start, $date_end){
                            if($date_start == $date_end){
                                if($today != $date_start){
                                    $liquidated->whereRaw("DATE(created_date) < CURDATE()");
                                }
                            }else {
                                $liquidated->whereRaw("DATE(created_date) BETWEEN '$date_start' AND '$date_end'");
                            }
                        })
                        ->whereIn('program_id', $program_ids)
                        ->groupBy('program_id')
                        ->get();

    	$program_funds = [];
        /*print_r($reg_code);
        print_r($program_ids);
        print_r($program_funds);
*/
        foreach ($funds as $k => $v) {
            $isMatch = 0;
            $frags   = [];
            foreach ($liquidated as $key => $val) {
                if($v->program_id == $val->program_id){
                    $isMatch = 1;
                    $frags   = $val;
                }
            }

            $program_funds[] = array(
                $v->program_shortname,
                array(
                    array(
                        'name' => 'Disbursed',
                        'y' => ($isMatch == 1) ? ($frags->total_liquidated / $v->total_amount) * 100 : 0,
                        'x' => ($isMatch == 1) ? '₱'.number_format($frags->total_liquidated, 2, '.', ',') : '₱0.00'
                    ),
                    array(
                        'name' => 'Remaining',
                        'y' => ($isMatch == 1) ? (($v->total_amount - $frags->total_liquidated) / $v->total_amount) * 100 : 100,
                        'x' => ($isMatch == 1) ? '₱'.number_format(($v->total_amount - $frags->total_liquidated), 2, '.', ',') : '₱'.number_format($v->total_amount, 2, '.', ',')
                    )
                )
            );
        }

    	return $program_funds;
    }

    public function get_upload_disbursed_data($has_special, $date_start, $date_end){
    	$program_ids = Session::get('program_id_sets');
    	if($has_special){
    		$data = $this->rffa_disbursed_data($program_ids[0], $date_start, $date_end);
    		return $data;
    	}
    }

    public function rffa_disbursed_data($program_id, $date_start, $date_end){
        $reg_code    = Session::get('region');
        $program_ids = Session::get('program_id_sets');

    	$year  = intval(date('Y'));
        $month = intval(date('m'));
        $today = date('Y-m-d');

        $date_label = [];
        $dates      = [];

        if($date_start == $date_end){
            $dcount = cal_days_in_month(CAL_GREGORIAN, $month, $year);
            for($d=1; $d<=$dcount; $d++){
                $time=mktime(12, 0, 0, $month, $d, $year);          
                if (date('m', $time)==$month)       
                    $date_label[] = date('M d, Y', $time);
                    $dates[]      = date('Y-m-d', $time);
            }
        }else {
            $interval = new DateInterval('P1D');
      
            $realEnd = new DateTime($date_end);
            $realEnd->add($interval);
          
            $period = new DatePeriod(new DateTime($date_start), $interval, $realEnd);

            foreach ($period as $key => $value) {
                array_push($date_label, $value->format('M d, Y'));   
                array_push($dates, $value->format('Y-m-d'));   
            }
        }
        
//DB::enableQueryLog();
        $uploaded = DB::table('kyc_profiles')
                      ->selectRaw('COUNT(kyc_id) as no_of_uploaded, reg_code, DATE(date_uploaded) as date_created')
                      ->where(function($uploaded) use($reg_code) {
                            if($reg_code != 13){
                                $uploaded->where('reg_code', $reg_code);
                            }
                      })
                      ->where(function($uploaded) use($today, $date_start, $date_end) {
                            if($date_start == $date_end){
                                if($today != $date_start){
                                    $uploaded->whereRaw("DATE(date_uploaded) < CURDATE()");
                                }
                            }else {
                                $uploaded->whereRaw("DATE(date_uploaded) BETWEEN '$date_start' AND '$date_end'");
                            }
                      })
                      ->groupByRaw('DATE(date_uploaded)')
                      ->get();
//dd(DB::getQueryLog());
        $disbursed = DB::table('excel_export')
                       ->selectRaw('COUNT(trans_id) as no_of_disbursed,reg_code,DATE(created_date) as date_created')
                       ->where(function($disbursed) use($reg_code) {
                            if($reg_code != 13){
                                $disbursed->where('reg_code', $reg_code);
                            }
                       })
                       ->where(function($disbursed) use($today, $date_start, $date_end) {
                            if($date_start == $date_end){
                                if($today != $date_start){
                                    $disbursed->whereRaw("DATE(created_date) < CURDATE()");
                                }
                            }else {
                                $disbursed->whereRaw("DATE(created_date) BETWEEN '$date_start' AND '$date_end'");
                            }
                       })
                       ->groupByRaw('DATE(created_date)')
                       ->get();

        $rows_uploaded  = [];
        $rows_disbursed = [];
        foreach ($dates as $date) {
            $isMatchUpd = 0;
            $updFrags   = [];
            foreach ($uploaded as $v) {
                if($date == $v->date_created){
                    $isMatchUpd = 1;
                    $updFrags   = $v;
                }
            }

            $rows_uploaded[] = array(
                ($isMatchUpd == 1) ? $updFrags->no_of_uploaded : 0
            );

            $isMatchDisb = 0;
            $disbFrags   = [];

            foreach ($disbursed as $v) {
                if($date == $v->date_created){
                    $isMatchDisb = 1;
                    $disbFrags  = $v;
                }
            }

            $rows_disbursed[] = array(
                ($isMatchDisb == 1) ? $disbFrags->no_of_disbursed : 0
            );
        }

        $data_uploaded = array(
            'name' => 'Uploaded',
            'data' => $rows_uploaded,
            'stack' => 'total_number'
        );

        $data_disbursed = array(
            'name' => 'Disbursed',
            'data' => $rows_disbursed,
            'stack' => 'total_number'
        );

        $data = array(
            'series'     => [$data_uploaded, $data_disbursed],
            'categories' => $date_label
        );

    	return $data;
    }

    public function get_pie_data($date_start, $date_end){
        $region_province = Session::get('provinces_on_region');
        $reg_code        = Session::get('region');
        $program_ids     = Session::get('program_id_sets');
        $today           = date('Y-m-d');

        $provinces = $region_province[Session::get('user_region_name')];

        if($reg_code != 13){
            $query = DB::table('excel_export as a')
                       ->leftJoin('kyc_profiles as b','a.kyc_id','=','b.kyc_id')
                       ->selectRaw('a.program_id, SUM(amount) as total_disbursed, b.prov_code, b.province, b.reg_code, b.region')
                       ->where(function($query) use($today, $date_start, $date_end){
                            if($date_start == $date_end){
                                if($today != $date_start){
                                    $query->whereRaw("DATE(a.created_date) < CURDATE()");
                                }
                            }else {
                                $query->whereRaw("DATE(a.created_date) BETWEEN '$date_start' AND '$date_end'");
                            }
                       })
                       ->groupByRaw('a.program_id, b.province')
                       ->get();

            $data = [];

            foreach ($provinces as $k => $v) {
                $isMatch = 0;
                $rows = [];
                foreach ($query as $val) {
                    if($k == $val->prov_code){
                        $isMatch = 1;
                        $rows = $val;
                    }
                }

                $data[] = array(
                    $v,
                    ($isMatch == 1) ? floatval($rows->total_disbursed) : 0
                );
            }
            
            return $data;
        }else {
            $regions = DB::table('geo_region')
                         ->where('active', '1')
                         ->get();

            $query = DB::table('excel_export as a')
                       ->leftJoin('kyc_profiles as b','a.kyc_id','=','b.kyc_id')
                       ->selectRaw('a.program_id, SUM(amount) as total_disbursed, b.prov_code, b.province, b.reg_code, b.region')
                       ->where(function($query) use($today, $date_start, $date_end){
                            if($date_start == $date_end){
                                if($today != $date_start){
                                    $query->whereRaw("DATE(a.created_date) < CURDATE()");
                                }
                            }else {
                                $query->whereRaw("DATE(a.created_date) BETWEEN '$date_start' AND '$date_end'");
                            }
                       })
                       ->groupByRaw('a.program_id, b.reg_code')
                       ->get();

            $data = [];
            foreach ($regions as $region) {
                $isMatch = 0;
                $rows    = [];
                foreach ($query as $k => $v) {
                    if($region->code_reg == $v->reg_code){
                        $isMatch = 1;
                        $rows    = $v;
                    }
                }

                $data[] = array(
                    $region->shortname,
                    ($isMatch == 1) ? floatval($rows->total_disbursed) : 0
                );
            }

            return $data;
        }
    }

    public function get_rfo_transactions_data($date_start, $date_end){
        $program_ids = Session::get('program_id_sets');
        $today       = date('Y-m-d');

        $regions = DB::table('geo_region')
                     ->where('active', '1')
                     ->get();

        $funds = DB::table('fund_source')
                   ->selectRaw('SUM(amount) as total_funds,SUM(no_of_farmers) as target_farmers, program_id, reg')
                   ->whereIn('program_id', $program_ids)
                   ->groupBy('program_id', 'reg')
                   ->get();

        $uploaded = DB::table('kyc_profiles')
                      ->selectRaw('COUNT(kyc_id) as total, reg_code')
                      ->where(function($uploaded) use($today, $date_start, $date_end) {
                            if($date_start == $date_end){
                                if($today != $date_start){
                                    $uploaded->whereRaw("DATE(date_uploaded) < CURDATE()");
                                }
                            }else {
                                $uploaded->whereRaw("DATE(date_uploaded) BETWEEN '$date_start' AND '$date_end'");
                            }
                      })
                      ->groupBy('reg_code')
                      ->get();

        $endorsed = DB::table('kyc_profiles')
                      ->selectRaw('SUM(isapproved) as total, reg_code')
                      ->where(function($endorsed) use($today, $date_start, $date_end) {
                            if($date_start == $date_end){
                                if($today != $date_start){
                                    $endorsed->whereRaw("DATE(approved_date) < CURDATE()");
                                }
                            }else {
                                $endorsed->whereRaw("DATE(approved_date) BETWEEN '$date_start' AND '$date_end'");
                            }
                      })
                      ->groupBy('reg_code')
                      ->get();

        $budget = DB::table('kyc_profiles')
                    ->selectRaw('SUM(approved_by_b) as total, reg_code')
                    ->where(function($budget) use($today, $date_start, $date_end) {
                        if($date_start == $date_end){
                            if($today != $date_start){
                                $budget->whereRaw("DATE(approved_date_b) < CURDATE()");
                            }
                        }else {
                            $budget->whereRaw("DATE(approved_date_b) BETWEEN '$date_start' AND '$date_end'");
                        }
                    })
                    ->groupBy('reg_code')
                    ->get();

        $disbursed = DB::table('kyc_profiles')
                       ->selectRaw('SUM(approved_by_d) as total, reg_code')
                       ->where(function($disbursed) use($today, $date_start, $date_end) {
                            if($date_start == $date_end){
                                if($today != $date_start){
                                    $disbursed->whereRaw("DATE(approved_date_d) < CURDATE()");
                                }
                            }else {
                                $disbursed->whereRaw("DATE(approved_date_d) BETWEEN '$date_start' AND '$date_end'");
                            }
                       })
                       ->groupBy('reg_code')
                       ->get();
                       
        $r_uploaded = array(
            'name' => 'Uploaded',
            'data' => $this->group_series($regions, $uploaded, $funds)
        );

        $r_endorsed = array(
            'name' => 'Endorsed',
            'data' => $this->group_series($regions, $endorsed, $funds)
        );

        $r_budget = array(
            'name' => 'Reviewed',
            'data' => $this->group_series($regions, $budget, $funds)
        );

        $r_disbursed = array(
            'name' => 'Approved',
            'data' => $this->group_series($regions, $disbursed, $funds)
        );

        $categories = [];

        foreach ($regions as $v) {
            $categories[] = $v->shortname;
        }

        $data['series']     = [$r_uploaded,$r_endorsed,$r_budget,$r_disbursed];
        $data['categories'] = $categories;
        //print_r($categories);
        return $data;
    }

    public function group_series($regions, $data, $funds = array()){
        $r_targets = [];
        $results   = [];

        foreach($funds as $targets){
            $isMatch   = 0;
            $fragments = [];
            foreach ($data as $v) {
                if($targets->reg == $v->reg_code){
                    $isMatch   = 1;
                    $fragments = $v;
                }
            }

            #$results[] = ($isMatch == 1) ? round(($fragments->total / $targets->target_farmers), 2) * 100  : 0;
            $r_targets[] = array(
                'region'   => $targets->reg,
                'targets'  => $targets->target_farmers,
                'utilized' => ($isMatch == 1) ?  $fragments->total : 0,
                'percent_utilized' => ($isMatch == 1) ?  round(($fragments->total / $targets->target_farmers) * 100, 2)  : 0
            );
        }

        foreach ($regions as $region) {
            $isMatch   = 0;
            $fragments = [];
            foreach ($r_targets as $v) {
                if($region->code_reg == $v['region']){
                    $isMatch   = 1;
                    $fragments = $v;
                }
            }

            $results[] = ($isMatch == 1) ? $fragments['percent_utilized'] : 0;
        }
        
        return $results;
    }
}
