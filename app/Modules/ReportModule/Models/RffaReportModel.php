<?php

namespace App\Modules\ReportModule\Models;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class RffaReportModel extends Model
{
    use HasFactory;

    // get_fintech_files_custom_range
    public function fintech_no_uploaded_file_and_no_disbursement_file_custom_range($program_ids, $fin_start_date, $fin_end_date){

        $region = session()->get('region');

        $query = DB::select("SELECT y.fintech, sum(y.xki) as no_of_uploaded_file, sum(y.xtr) as no_of_disbursement_file, y.xkdu as date_uploaded, y.xdda as date
                                FROM (
                                        SELECT x.fp as fintech, count(x.ki) as xki, count(x.tr) as xtr, x.kdu as xkdu, x.dda as xdda
                                        FROM (
                                                SELECT 
                                                kp.fintech_provider as fp,
                                                kp.kyc_id as ki,
                                                dbp.total_records as tr,
                                                kp.kyc_id,
                                                dbp.dbp_batch_id as dbp_batch_id,
                                                date(kp.date_uploaded) as kdu,
                                                dbp.date as dda
                                                FROM kyc_profiles as kp
                                                LEFT JOIN dbp_batch as dbp on dbp.dbp_batch_id = kp.dbp_batch_id

                                                WHERE date(kp.date_uploaded) BETWEEN '$fin_start_date' AND '$fin_end_date' OR dbp.date BETWEEN '$fin_start_date' AND '$fin_end_date'

                                                GROUP BY kp.dbp_batch_id ORDER BY kp.fintech_provider

                                        ) as x GROUP BY x.dbp_batch_id ORDER BY x.fp
                                ) as y GROUP BY y.fintech");

        return $query;

    }
    
    // get_fintech_files_by_today
    public function get_fintech_files_by_today($program_ids, $fin_start_date, $fin_end_date){

        $region = session()->get('region');

        $query = DB::select("SELECT y.fintech, sum(y.xki) as no_of_uploaded_file, sum(y.xtr) as no_of_disbursement_file, y.xkdu as date_uploaded, y.xdda as date
                                FROM (
                                        SELECT x.fp as fintech, count(x.ki) as xki, count(x.tr) as xtr, x.kdu as xkdu, x.dda as xdda
                                        FROM (
                                                SELECT 
                                                kp.fintech_provider as fp,
                                                kp.kyc_id as ki,
                                                dbp.total_records as tr,
                                                kp.kyc_id,
                                                dbp.dbp_batch_id as dbp_batch_id,
                                                date(kp.date_uploaded) as kdu,
                                                dbp.date as dda
                                                FROM kyc_profiles as kp
                                                LEFT JOIN dbp_batch as dbp on dbp.dbp_batch_id = kp.dbp_batch_id

                                                WHERE date(kp.date_uploaded) BETWEEN '$fin_start_date' AND '$fin_end_date' OR dbp.date BETWEEN '$fin_start_date' AND '$fin_end_date'

                                                GROUP BY kp.dbp_batch_id ORDER BY kp.fintech_provider

                                        ) as x GROUP BY x.dbp_batch_id ORDER BY x.fp
                                ) as y GROUP BY y.fintech");
        
        return $query;

    }

    // get_fintech_files_by_yesterday
    public function get_fintech_files_by_yesterday($program_ids, $fin_start_date, $fin_end_date){

        $region = session()->get('region');

        $query = DB::select("SELECT y.fintech, sum(y.xki) as no_of_uploaded_file, sum(y.xtr) as no_of_disbursement_file, y.xkdu as date_uploaded, y.xdda as date
                                FROM (
                                        SELECT x.fp as fintech, count(x.ki) as xki, count(x.tr) as xtr, x.kdu as xkdu, x.dda as xdda
                                        FROM (
                                                SELECT 
                                                kp.fintech_provider as fp,
                                                kp.kyc_id as ki,
                                                dbp.total_records as tr,
                                                kp.kyc_id,
                                                dbp.dbp_batch_id as dbp_batch_id,
                                                date(kp.date_uploaded) as kdu,
                                                dbp.date as dda
                                                FROM kyc_profiles as kp
                                                LEFT JOIN dbp_batch as dbp on dbp.dbp_batch_id = kp.dbp_batch_id

                                                WHERE date(kp.date_uploaded) BETWEEN '$fin_start_date' AND '$fin_end_date' OR dbp.date BETWEEN '$fin_start_date' AND '$fin_end_date'

                                                GROUP BY kp.dbp_batch_id ORDER BY kp.fintech_provider

                                        ) as x GROUP BY x.dbp_batch_id ORDER BY x.fp
                                ) as y GROUP BY y.fintech");

        return $query;

    }
    
    // get_fintech_files_by_last_7_days
    public function get_fintech_files_by_last_7_days($program_ids, $fin_start_date, $fin_end_date){

        $region = session()->get('region');
        
        $query = DB::select("SELECT y.fintech, sum(y.xki) as no_of_uploaded_file, sum(y.xtr) as no_of_disbursement_file, y.xkdu as date_uploaded, y.xdda as date
                                FROM (
                                        SELECT x.fp as fintech, count(x.ki) as xki, count(x.tr) as xtr, x.kdu as xkdu, x.dda as xdda
                                        FROM (
                                                SELECT 
                                                kp.fintech_provider as fp,
                                                kp.kyc_id as ki,
                                                dbp.total_records as tr,
                                                kp.kyc_id,
                                                dbp.dbp_batch_id as dbp_batch_id,
                                                date(kp.date_uploaded) as kdu,
                                                dbp.date as dda
                                                FROM kyc_profiles as kp
                                                LEFT JOIN dbp_batch as dbp on dbp.dbp_batch_id = kp.dbp_batch_id

                                                WHERE date(kp.date_uploaded) BETWEEN '$fin_start_date' AND '$fin_end_date' OR dbp.date BETWEEN '$fin_start_date' AND '$fin_end_date'

                                                GROUP BY kp.dbp_batch_id ORDER BY kp.fintech_provider

                                        ) as x GROUP BY x.dbp_batch_id ORDER BY x.fp
                                ) as y GROUP BY y.fintech");

        return $query;

    }

    // get_fintech_files_by_last_30_days
    public function get_fintech_files_by_last_30_days($program_ids, $fin_start_date, $fin_end_date){

        $region = session()->get('region');

        $query = DB::select("SELECT y.fintech, sum(y.xki) as no_of_uploaded_file, sum(y.xtr) as no_of_disbursement_file, y.xkdu as date_uploaded, y.xdda as date
                                FROM (
                                        SELECT x.fp as fintech, count(x.ki) as xki, count(x.tr) as xtr, x.kdu as xkdu, x.dda as xdda
                                        FROM (
                                                SELECT 
                                                kp.fintech_provider as fp,
                                                kp.kyc_id as ki,
                                                dbp.total_records as tr,
                                                kp.kyc_id,
                                                dbp.dbp_batch_id as dbp_batch_id,
                                                date(kp.date_uploaded) as kdu,
                                                dbp.date as dda
                                                FROM kyc_profiles as kp
                                                LEFT JOIN dbp_batch as dbp on dbp.dbp_batch_id = kp.dbp_batch_id

                                                WHERE date(kp.date_uploaded) BETWEEN '$fin_start_date' AND '$fin_end_date' OR dbp.date BETWEEN '$fin_start_date' AND '$fin_end_date'

                                                GROUP BY kp.dbp_batch_id ORDER BY kp.fintech_provider

                                        ) as x GROUP BY x.dbp_batch_id ORDER BY x.fp
                                ) as y GROUP BY y.fintech");

        return $query;
    
    }

    // get_fintech_files_by_this_month
    public function get_fintech_files_by_this_month($program_ids, $fin_start_date, $fin_end_date){
        $region = session()->get('region');

        $query = DB::select("SELECT y.fintech, sum(y.xki) as no_of_uploaded_file, sum(y.xtr) as no_of_disbursement_file, y.xkdu as date_uploaded, y.xdda as date
                                FROM (
                                        SELECT x.fp as fintech, count(x.ki) as xki, count(x.tr) as xtr, x.kdu as xkdu, x.dda as xdda
                                        FROM (
                                                SELECT 
                                                kp.fintech_provider as fp,
                                                kp.kyc_id as ki,
                                                dbp.total_records as tr,
                                                kp.kyc_id,
                                                dbp.dbp_batch_id as dbp_batch_id,
                                                date(kp.date_uploaded) as kdu,
                                                dbp.date as dda
                                                FROM kyc_profiles as kp
                                                LEFT JOIN dbp_batch as dbp on dbp.dbp_batch_id = kp.dbp_batch_id

                                                WHERE date(kp.date_uploaded) BETWEEN '$fin_start_date' AND '$fin_end_date' OR dbp.date BETWEEN '$fin_start_date' AND '$fin_end_date'

                                                GROUP BY kp.dbp_batch_id ORDER BY kp.fintech_provider

                                        ) as x GROUP BY x.dbp_batch_id ORDER BY x.fp
                                ) as y GROUP BY y.fintech");
        
        return $query;
    }

     // get fintech files default selected filter by date : This month 
    public function get_fintech_files_by_this_month_02($program_ids){
        $region = session()->get('region');

        $query = DB::select("SELECT y.fintech, sum(y.xki) as no_of_uploaded_file, sum(y.xtr) as no_of_disbursement_file, y.xkdu as date_uploaded, y.xdda as date
                                FROM (
                                        SELECT x.fp as fintech, count(x.ki) as xki, count(x.tr) as xtr, x.kdu as xkdu, x.dda as xdda
                                        FROM (
                                                SELECT 
                                                kp.fintech_provider as fp,
                                                kp.kyc_id as ki,
                                                dbp.total_records as tr,
                                                kp.kyc_id,
                                                dbp.dbp_batch_id as dbp_batch_id,
                                                date(kp.date_uploaded) as kdu,
                                                dbp.date as dda
                                                FROM kyc_profiles as kp
                                                LEFT JOIN dbp_batch as dbp on dbp.dbp_batch_id = kp.dbp_batch_id

                                                WHERE MONTH(kp.date_uploaded) = MONTH(NOW()) OR MONTH(dbp.date) = MONTH(NOW())

                                                GROUP BY kp.dbp_batch_id ORDER BY kp.fintech_provider

                                        ) as x GROUP BY x.dbp_batch_id ORDER BY x.fp
                                ) as y GROUP BY y.fintech");
        
        return $query;
    }

    // get_fintech_files_by_last_month
    public function get_fintech_files_by_last_month($program_ids, $fin_start_date, $fin_end_date){

        $region = session()->get('region');

        $query = DB::select("SELECT y.fintech, sum(y.xki) as no_of_uploaded_file, sum(y.xtr) as no_of_disbursement_file, y.xkdu as date_uploaded, y.xdda as date
                                FROM (
                                        SELECT x.fp as fintech, count(x.ki) as xki, count(x.tr) as xtr, x.kdu as xkdu, x.dda as xdda
                                        FROM (
                                                SELECT 
                                                kp.fintech_provider as fp,
                                                kp.kyc_id as ki,
                                                dbp.total_records as tr,
                                                kp.kyc_id,
                                                dbp.dbp_batch_id as dbp_batch_id,
                                                date(kp.date_uploaded) as kdu,
                                                dbp.date as dda
                                                FROM kyc_profiles as kp
                                                LEFT JOIN dbp_batch as dbp on dbp.dbp_batch_id = kp.dbp_batch_id

                                                WHERE date(kp.date_uploaded) BETWEEN '$fin_start_date' AND '$fin_end_date' OR dbp.date BETWEEN '$fin_start_date' AND '$fin_end_date'

                                                GROUP BY kp.dbp_batch_id ORDER BY kp.fintech_provider

                                        ) as x GROUP BY x.dbp_batch_id ORDER BY x.fp
                                ) as y GROUP BY y.fintech");

        return $query; 

    }




    // get_fintech_records_custom_range
    public function fintech_no_records_uploaded_and_no_records_disbursed($program_ids, $fin_start_date, $fin_end_date){
        $region = session()->get('region');

        $query = DB::select("SELECT x.fp as fintech,
                                count(case when x.kdu between '$fin_start_date' and '$fin_end_date' then 1 end) as no_of_records_uploaded, 
                                count(case when x.dda between '$fin_start_date' and '$fin_end_date' then 1 end) as no_of_records_disbursed
                                FROM (
                                        SELECT 
                                            kp.fintech_provider as fp,
                                            kp.kyc_id as ki,
                                            dbp.dbp_batch_id as tr,
                                            kp.kyc_id,
                                            dbp.dbp_batch_id as dbp_batch_id,
                                            date(kp.date_uploaded) as kdu,
                                            dbp.date as dda
                                        FROM kyc_profiles as kp
                                        LEFT JOIN dbp_batch as dbp on dbp.dbp_batch_id = kp.dbp_batch_id
                                        
                                        WHERE date(kp.date_uploaded) BETWEEN '$fin_start_date' AND '$fin_end_date' OR dbp.date BETWEEN '$fin_start_date' AND '$fin_end_date'
                                        
                                        ORDER BY kp.fintech_provider
                                        
                                ) as x group by x.fp ORDER BY x.fp");

        return $query;
    }

        // get_fintech_records_by_today
    public function get_fintech_records_by_today($program_ids, $fin_start_date, $fin_end_date){

        $region = session()->get('region');

        $query = DB::select("SELECT x.fp as fintech,
                                count(case when x.kdu between '$fin_start_date' and '$fin_end_date' then 1 end) as no_of_records_uploaded, 
                                count(case when x.dda between '$fin_start_date' and '$fin_end_date' then 1 end) as no_of_records_disbursed
                                FROM (
                                        SELECT 
                                            kp.fintech_provider as fp,
                                            kp.kyc_id as ki,
                                            dbp.dbp_batch_id as tr,
                                            kp.kyc_id,
                                            dbp.dbp_batch_id as dbp_batch_id,
                                            date(kp.date_uploaded) as kdu,
                                            dbp.date as dda
                                        FROM kyc_profiles as kp
                                        LEFT JOIN dbp_batch as dbp on dbp.dbp_batch_id = kp.dbp_batch_id
                                        
                                        WHERE date(kp.date_uploaded) BETWEEN '$fin_start_date' AND '$fin_end_date' OR dbp.date BETWEEN '$fin_start_date' AND '$fin_end_date'
                                        
                                        ORDER BY kp.fintech_provider
                                        
                                ) as x group by x.fp ORDER BY x.fp");
        
        return $query;

    }

    // get_fintech_records_by_yesterday
    public function get_fintech_records_by_yesterday($program_ids, $fin_start_date, $fin_end_date){

        $region = session()->get('region');

        $query = DB::select("SELECT x.fp as fintech,
                                count(case when x.kdu between '$fin_start_date' and '$fin_end_date' then 1 end) as no_of_records_uploaded, 
                                count(case when x.dda between '$fin_start_date' and '$fin_end_date' then 1 end) as no_of_records_disbursed
                                FROM (
                                        SELECT 
                                            kp.fintech_provider as fp,
                                            kp.kyc_id as ki,
                                            dbp.dbp_batch_id as tr,
                                            kp.kyc_id,
                                            dbp.dbp_batch_id as dbp_batch_id,
                                            date(kp.date_uploaded) as kdu,
                                            dbp.date as dda
                                        FROM kyc_profiles as kp
                                        LEFT JOIN dbp_batch as dbp on dbp.dbp_batch_id = kp.dbp_batch_id
                                        
                                        WHERE date(kp.date_uploaded) BETWEEN '$fin_start_date' AND '$fin_end_date' OR dbp.date BETWEEN '$fin_start_date' AND '$fin_end_date'
                                        
                                        ORDER BY kp.fintech_provider
                                        
                                ) as x group by x.fp ORDER BY x.fp");
    
        return $query;

    }
    
    // get_fintech_records_by_last_7_days
    public function get_fintech_records_by_last_7_days($program_ids, $fin_start_date, $fin_end_date){

        $region = session()->get('region');

        $query = DB::select("SELECT x.fp as fintech,
                                count(case when x.kdu between '$fin_start_date' and '$fin_end_date' then 1 end) as no_of_records_uploaded, 
                                count(case when x.dda between '$fin_start_date' and '$fin_end_date' then 1 end) as no_of_records_disbursed
                                FROM (
                                        SELECT 
                                            kp.fintech_provider as fp,
                                            kp.kyc_id as ki,
                                            dbp.dbp_batch_id as tr,
                                            kp.kyc_id,
                                            dbp.dbp_batch_id as dbp_batch_id,
                                            date(kp.date_uploaded) as kdu,
                                            dbp.date as dda
                                        FROM kyc_profiles as kp
                                        LEFT JOIN dbp_batch as dbp on dbp.dbp_batch_id = kp.dbp_batch_id
                                        
                                        WHERE date(kp.date_uploaded) BETWEEN '$fin_start_date' AND '$fin_end_date' OR dbp.date BETWEEN '$fin_start_date' AND '$fin_end_date'
                                        
                                        ORDER BY kp.fintech_provider
                                        
                                ) as x group by x.fp ORDER BY x.fp");

        return $query;

    }

    // get_fintech_records_by_last_30_days
    public function get_fintech_records_by_last_30_days($program_ids, $fin_start_date, $fin_end_date){

        $region = session()->get('region');

        $query = DB::select("SELECT x.fp as fintech,
                                count(case when x.kdu between '$fin_start_date' and '$fin_end_date' then 1 end) as no_of_records_uploaded, 
                                count(case when x.dda between '$fin_start_date' and '$fin_end_date' then 1 end) as no_of_records_disbursed
                                FROM (
                                        SELECT 
                                            kp.fintech_provider as fp,
                                            kp.kyc_id as ki,
                                            dbp.dbp_batch_id as tr,
                                            kp.kyc_id,
                                            dbp.dbp_batch_id as dbp_batch_id,
                                            date(kp.date_uploaded) as kdu,
                                            dbp.date as dda
                                        FROM kyc_profiles as kp
                                        LEFT JOIN dbp_batch as dbp on dbp.dbp_batch_id = kp.dbp_batch_id
                                        
                                        WHERE date(kp.date_uploaded) BETWEEN '$fin_start_date' AND '$fin_end_date' OR dbp.date BETWEEN '$fin_start_date' AND '$fin_end_date'
                                        
                                        ORDER BY kp.fintech_provider
                                        
                                ) as x group by x.fp ORDER BY x.fp");

        return $query;
    
    }

    // get fintech records default selected filter by date : This month 
    public function get_fintech_records_by_this_month_02($program_ids){
        $region = session()->get('region');

        $query = DB::select("SELECT x.fp as fintech,
                                count(case when MONTH(x.kdu) = MONTH(NOW()) then 1 end) as no_of_records_uploaded, 
                                count(case when MONTH(x.dda) = MONTH(NOW()) then 1 end) as no_of_records_disbursed
                                FROM (
                                        SELECT 
                                            kp.fintech_provider as fp,
                                            kp.kyc_id as ki,
                                            dbp.dbp_batch_id as tr,
                                            kp.kyc_id,
                                            dbp.dbp_batch_id as dbp_batch_id,
                                            date(kp.date_uploaded) as kdu,
                                            dbp.date as dda
                                        FROM kyc_profiles as kp
                                        LEFT JOIN dbp_batch as dbp on dbp.dbp_batch_id = kp.dbp_batch_id
                                        
                                        WHERE MONTH(kp.date_uploaded) = MONTH(NOW()) OR MONTH(dbp.date) = MONTH(NOW())
                                        
                                        ORDER BY kp.fintech_provider
                                        
                                ) as x group by x.fp ORDER BY x.fp");
        
        return $query;
    }

    // get_fintech_records_by_this_month
    public function get_fintech_records_by_this_month($program_ids, $fin_start_date, $fin_end_date){

        $region = session()->get('region');
        
        $query = DB::select("SELECT x.fp as fintech,
                                count(case when x.kdu between '$fin_start_date' and '$fin_end_date' then 1 end) as no_of_records_uploaded, 
                                count(case when x.dda between '$fin_start_date' and '$fin_end_date' then 1 end) as no_of_records_disbursed
                                FROM (
                                        SELECT 
                                            kp.fintech_provider as fp,
                                            kp.kyc_id as ki,
                                            dbp.dbp_batch_id as tr,
                                            kp.kyc_id,
                                            dbp.dbp_batch_id as dbp_batch_id,
                                            date(kp.date_uploaded) as kdu,
                                            dbp.date as dda
                                        FROM kyc_profiles as kp
                                        LEFT JOIN dbp_batch as dbp on dbp.dbp_batch_id = kp.dbp_batch_id
                                        
                                        WHERE date(kp.date_uploaded) BETWEEN '$fin_start_date' AND '$fin_end_date' OR dbp.date BETWEEN '$fin_start_date' AND '$fin_end_date'
                                        
                                        ORDER BY kp.fintech_provider
                                        
                                ) as x group by x.fp ORDER BY x.fp");
        
        return $query;
    }

    // get_fintech_records_by_last_month
    public function get_fintech_records_by_last_month($program_ids, $fin_start_date, $fin_end_date){

        $region = session()->get('region');

        $query = DB::select("SELECT x.fp as fintech,
                                count(case when x.kdu between '$fin_start_date' and '$fin_end_date' then 1 end) as no_of_records_uploaded, 
                                count(case when x.dda between '$fin_start_date' and '$fin_end_date' then 1 end) as no_of_records_disbursed
                                FROM (
                                        SELECT 
                                            kp.fintech_provider as fp,
                                            kp.kyc_id as ki,
                                            dbp.dbp_batch_id as tr,
                                            kp.kyc_id,
                                            dbp.dbp_batch_id as dbp_batch_id,
                                            date(kp.date_uploaded) as kdu,
                                            dbp.date as dda
                                        FROM kyc_profiles as kp
                                        LEFT JOIN dbp_batch as dbp on dbp.dbp_batch_id = kp.dbp_batch_id
                                        
                                        WHERE date(kp.date_uploaded) BETWEEN '$fin_start_date' AND '$fin_end_date' OR dbp.date BETWEEN '$fin_start_date' AND '$fin_end_date'
                                        
                                        ORDER BY kp.fintech_provider
                                        
                                ) as x group by x.fp ORDER BY x.fp");

        return $query; 

    }




    // get by region CO_program_focal_summary
    public function get_by_region_co_program_focal_summary($program_ids){

        $query = DB::table('kyc_profiles as kp')
                        ->select('kp.region', 'kp.fintech_provider', DB::raw('COUNT(kp.region) as no_of_uploads_kyc'),
                                DB::raw('COUNT(ee.amount) as no_of_disbursed'), DB::raw('SUM(ifnull(ee.amount, 0)) as total_disbursed_amount'))
                        
                        ->leftJoin('excel_export as ee', 'kp.kyc_id', 'ee.kyc_id')
                        ->groupBy('kp.region')
                        ->orderBy('kp.region')
                        ->get();

        return $query;
    }

    /**
     *  If RFO Program Focal => shows only "By Province"
     *  Else CO Program Focal => shows only "By Region and Province"
     */
    public function get_by_rfo_or_co_progam_focal_summary($program_ids){
        $region = session()->get('region');

        $region_name = session()->get('reg_reg');
        
        $provinces_on_region = session()->get('provinces_on_region');
        
        $prov = [];
        foreach($provinces_on_region as $key => $val){
            foreach($val as $v){
                array_push($prov, $v);
            }
        }

        $get_all_province = DB::table('kyc_profiles')
                        ->select('province')
                        ->groupBy('province')
                        ->get();
              
        $kyc_province = [];
        foreach($get_all_province as  $val){
            array_push($kyc_province, $val->province);
        }

        $get_all_region = DB::table('kyc_profiles')
                                    ->select('region')
                                    ->groupBy('region')
                                    ->get();

        $kyc_region = [];
        foreach($get_all_region as $val){
            array_push($kyc_region, $val->region);
        }
        
        foreach($region_name as $row){
            $region_name = $row->reg_name;
            
            $query = DB::table('kyc_profiles as kp')
                                ->select('kp.region','kp.province', 'kp.fintech_provider',
                                        DB::raw('COUNT(kp.kyc_id) as no_of_uploads_kyc'),
                                        DB::raw('COUNT(case when ee.kyc_id is not null then 1 end) as no_of_disbursed'),
                                        DB::raw('SUM(ifnull(ee.amount, 0)) as total_disbursed_amount'))
                                ->leftJoin('excel_export as ee', 'kp.kyc_id', 'ee.kyc_id')
                                ->when($region, function($query, $region) use($region_name, $prov, $program_ids){
                                    if($region != 13){
                                        $query->where('kp.region', '=', $region_name)
                                              ->whereIn('kp.province', $prov)
                                            //   ->whereIn('ee.program_id', $program_ids)
                                              ->groupBy('kp.region', 'kp.province')
                                              ->orderBY('kp.region');
                                    }
                                    else{
                                        $query
                                            //   ->whereIn('ee.program_id', $program_ids)
                                              ->groupBy('kp.region', 'kp.province')
                                              ->orderBY('kp.region')
                                              ->orderBY('kp.province');
                                    }
                                })
                                ->get();
        }

        $data = [];

        if($region != 13){
            foreach($prov as $val){
                $isMatch = 0;
    
                $fragments = [];
    
                foreach($query as $row){
                    if($val == $row->province){
                        $isMatch = 1;
    
                        $fragments = $row;
                    }
                }
                $data[] = array(
                    'province' => $val,
                    'no_of_uploads_kyc' => ($isMatch == 1) ? $fragments->no_of_uploads_kyc : 0,
                    'no_of_disbursed' => ($isMatch == 1) ? $fragments->no_of_disbursed : 0,
                    'total_disbursed_amount' => ($isMatch == 1) ? $fragments->total_disbursed_amount : 0,
                );
            }
        }
        else{
            return $query;
            // foreach($kyc_region as $region){
            //     foreach($kyc_province as $province){
            //         $isMatch = 0;
        
            //         $fragments = [];

            //         foreach($query as $row){
            //             if($province == $row->province){
            //                 $isMatch = 1;
        
            //                 $fragments = $row;
            //             }
            //         }
        
            //         $data[] = array(
            //             'region' => $region,
            //             'province' => $province,
            //             'no_of_uploads_kyc' => ($isMatch == 1) ? $fragments->no_of_uploads_kyc : 0,
            //             'no_of_disbursed' => ($isMatch == 1) ? $fragments->no_of_disbursed : 0,
            //             'total_disbursed_amount' => ($isMatch == 1) ? $fragments->total_disbursed_amount : 0,
            //         );
            //     }
            // }
        }

        return $data;
    }

    // CO_program_focal_summary: get by region, province, municipality, and barangay
    public function get_by_region_province_municipality_and_barangay_co_program_focal_summary($program_ids){

        $query = DB::table('kyc_profiles as kp')
                        ->select('kp.region','kp.province', 'kp.municipality','kp.barangay', 'kp.fintech_provider',
                            DB::raw('COUNT(kp.kyc_id) as no_of_uploads_kyc'),
                            DB::raw('COUNT(case when ee.kyc_id is not null then 1 end) as no_of_disbursed'),
                            DB::raw('SUM(ifnull(ee.amount, 0)) as total_disbursed_amount'))
                        ->leftJoin('excel_export as ee', 'kp.kyc_id', 'ee.kyc_id')
                        ->groupBy('kp.region', 'kp.province', 'kp.municipality','kp.barangay')
                        ->orderByRaw('kp.region ASC')
                        ->orderByRaw('kp.province ASC')
                        ->orderByRaw('kp.municipality ASC')
                        ->get();

        return $query;
    }

    public function get_by_custom_range($program_ids, $start_date, $end_date){
        $region = session()->get('region');

        $region_name = session()->get('reg_reg');
       
        $provinces_on_region = session()->get('provinces_on_region');

        $prov_name = [];
        foreach($provinces_on_region as $key => $val){
            foreach($val as $v){
                array_push($prov_name, $v);
            }
        }
       
        foreach($region_name as $row){
            $region_name = $row->reg_name;
            
            $query = DB::table('kyc_profiles as kp')
                            ->select(DB::raw('DATE_FORMAT(kp.date_uploaded, "%M-%d-%Y") as day'), 
                                DB::raw('COUNT(kp.kyc_id) as no_of_uploads_kyc'),
                                DB::raw('COUNT(case when ee.kyc_id is not null then 1 end) as no_of_disbursed'),
                                DB::raw('SUM(ifnull(ee.amount, 0)) as total_disbursed_amount'))
                            ->leftJoin('excel_export as ee', 'kp.kyc_id', 'ee.kyc_id')
                            ->when($region, function($query, $region) use($region_name, $prov_name, $start_date, $end_date){
                                if($region != 13){
                                    if($start_date == null && $end_date == null){
                                        $query
                                            ->where('kp.region', '=', $region_name)
                                            ->whereIn('kp.province', $prov_name)
                                            ->groupBy(DB::raw('MONTH(ee.created_date)'), DB::raw('DAY(ee.created_date)'), DB::raw('YEAR(ee.created_date)'))
                                            ->orderBy('ee.created_date');
                                    }else{
                                        $query
                                            ->where('kp.region', '=', $region_name)
                                            ->whereIn('kp.province', $prov_name)
                                            ->whereDate('kp.date_uploaded', '>=', $start_date)
                                            ->whereDate('kp.date_uploaded', '<=', $end_date)
                                            ->groupBy(DB::raw('MONTH(ee.created_date)'), DB::raw('DAY(ee.created_date)'), DB::raw('YEAR(ee.created_date)'))
                                            ->orderBy('ee.created_date');
                                    }
                                }else{
                                        if($start_date == null && $end_date == null){
                                            $query->groupBy(DB::raw('MONTH(kp.date_uploaded)'), DB::raw('DAY(kp.date_uploaded)'), DB::raw('YEAR(kp.date_uploaded)'))
                                                  ->orderBy('kp.date_uploaded');
                                        }else{
                                            $query
                                                ->whereDate('kp.date_uploaded', '>=', $start_date)
                                                ->whereDate('kp.date_uploaded', '<=', $end_date)
                                                ->groupBy(DB::raw('MONTH(kp.date_uploaded)'), DB::raw('DAY(kp.date_uploaded)'), DB::raw('YEAR(kp.date_uploaded)'))
                                                ->orderBy('kp.date_uploaded');
                                        }
                                }
                            })
                            ->get();
        }
        return $query;
    }

    public function get_by_monthly($program_ids){
        $region = session()->get('region');

        $region_name = session()->get('reg_reg');
       
        $provinces_on_region = session()->get('provinces_on_region');

        $prov = [];
        foreach($provinces_on_region as $key => $val){
            foreach($val as $v){
                array_push($prov, $v);
            }
        }
       
        foreach($region_name as $row){
            $region_name = $row->reg_name;
            
            $query = DB::table('kyc_profiles as kp')
                            ->select(DB::raw('DATE_FORMAT(kp.date_uploaded, "%M-%Y") as month'), 
                                     DB::raw('COUNT(kp.kyc_id) as no_of_uploads_kyc'),
                                     DB::raw('COUNT(case when ee.kyc_id is not null then 1 end) as no_of_disbursed'),
                                     DB::raw('SUM(ifnull(ee.amount, 0)) as total_disbursed_amount'))
                            ->leftJoin('excel_export as ee', 'kp.kyc_id', 'ee.kyc_id')
                            ->when($region, function($query, $region) use($region_name, $prov, $program_ids){
                                if($region != 13){
                                    $query
                                        ->where('kp.region', '=', $region_name)
                                        ->whereIn('kp.province', $prov)
                                        ->groupBy(DB::raw('YEAR(kp.date_uploaded)'), DB::raw('MONTH(kp.date_uploaded)'))
                                        ->orderBy('kp.date_uploaded');
                                }else{
                                    $query
                                        ->groupBy(DB::raw('YEAR(kp.date_uploaded)'), DB::raw('MONTH(kp.date_uploaded)'))
                                        ->orderBy('kp.date_uploaded');
                                }
                            })
                            ->get();
        }

        return $query;
    }

    public function get_by_today($program_ids){
        $region = session()->get('region');

        $region_name = session()->get('reg_reg');
       
        $provinces_on_region = session()->get('provinces_on_region');

        $prov = [];
        foreach($provinces_on_region as $key => $val){
            foreach($val as $v){
                array_push($prov, $v);
            }
        }
       
        foreach($region_name as $row){
            $region_name = $row->reg_name;
            
            $query = DB::table('kyc_profiles as kp')
                            ->select(DB::raw('DATE_FORMAT(kp.date_uploaded, "%M-%d-%Y") as today'), 
                                DB::raw('COUNT(kp.kyc_id) as no_of_uploads_kyc'),
                                DB::raw('COUNT(case when ee.kyc_id is not null then 1 end) as no_of_disbursed'),
                                DB::raw('SUM(ifnull(ee.amount, 0)) as total_disbursed_amount'))
                            ->leftJoin('excel_export as ee', 'kp.kyc_id', 'ee.kyc_id')
                            ->when($region, function($query, $region) use($region_name, $prov, $program_ids){
                                if($region != 13){
                                    $query
                                        ->where('kp.region', '=', $region_name)
                                        // ->whereIn('kp.province', $prov)
                                        ->whereRaw('DATE(kp.date_uploaded) = CURDATE() ')
                                        ->groupBy(DB::raw('YEAR(ee.created_date)'), DB::raw('MONTH(ee.created_date)'))
                                        ->orderBy('kp.date_uploaded');
                                }else{
                                    $query
                                        ->whereRaw('DATE(kp.date_uploaded) = CURDATE() ')
                                        ->groupBy(DB::raw('MONTH(kp.date_uploaded)'), DB::raw('DAY(kp.date_uploaded)'), DB::raw('YEAR(kp.date_uploaded)'))
                                        ->orderBy('kp.date_uploaded');
                                }
                            })
                            ->get();
        }

        return $query;
    }

    public function get_by_yesterday($program_ids){
        $region = session()->get('region');

        $region_name = session()->get('reg_reg');
       
        $provinces_on_region = session()->get('provinces_on_region');

        $prov = [];
        foreach($provinces_on_region as $key => $val){
            foreach($val as $v){
                array_push($prov, $v);
            }
        }
       
        foreach($region_name as $row){
            $region_name = $row->reg_name;
            
            $query = DB::table('kyc_profiles as kp')
                        ->select(DB::raw('DATE_FORMAT(kp.date_uploaded, "%M-%d-%Y") as yesterday'), 
                            DB::raw('COUNT(kp.kyc_id) as no_of_uploads_kyc'),
                            DB::raw('COUNT(case when ee.kyc_id is not null then 1 end) as no_of_disbursed'),
                            DB::raw('SUM(ifnull(ee.amount, 0)) as total_disbursed_amount'))
                            ->leftJoin('excel_export as ee', 'kp.kyc_id', 'ee.kyc_id')
                            ->when($region, function($query, $region) use($region_name, $prov, $program_ids){
                                if($region != 13){
                                    $query
                                        ->where('kp.region', '=', $region_name)
                                        // ->whereIn('kp.province', $prov)
                                        ->where('kp.date_uploaded', 'DATE_SUB(CURDATE(), INTERVAL 1 DAY)')
                                        ->groupBy(DB::raw('MONTH(kp.date_uploaded)'), DB::raw('DAY(kp.date_uploaded)'), DB::raw('YEAR(kp.date_uploaded)'))
                                        ->orderBy('kp.date_uploaded');
                                }else{
                                    $query
                                        ->whereRaw('kp.date_uploaded BETWEEN DATE_SUB(CURDATE(), INTERVAL 1 DAY) AND CURDATE()')
                                        ->groupBy(DB::raw('MONTH(kp.date_uploaded)'), DB::raw('DAY(kp.date_uploaded)'), DB::raw('YEAR(kp.date_uploaded)'))
                                        ->orderBy('kp.date_uploaded');
                                }
                            })
                            ->get();
        }

        return $query;
    }

    public function get_by_last_7_days($program_ids){
        $region = session()->get('region');

        $region_name = session()->get('reg_reg');
       
        $provinces_on_region = session()->get('provinces_on_region');

        $prov = [];
        foreach($provinces_on_region as $key => $val){
            foreach($val as $v){
                array_push($prov, $v);
            }
        }
       
        foreach($region_name as $row){
            $region_name = $row->reg_name;
            
            $query = DB::table('kyc_profiles as kp')
                            ->select(DB::raw('DATE_FORMAT(kp.date_uploaded, "%M-%d-%Y") as last_7_days'), 
                                DB::raw('COUNT(kp.kyc_id) as no_of_uploads_kyc'),
                                DB::raw('COUNT(case when ee.kyc_id is not null then 1 end) as no_of_disbursed'),
                                DB::raw('SUM(ifnull(ee.amount, 0)) as total_disbursed_amount'))
                            ->leftJoin('excel_export as ee', 'kp.kyc_id', 'ee.kyc_id')
                            ->when($region, function($query, $region) use($region_name, $prov, $program_ids){
                                if($region != 13){
                                    $query
                                        ->where('kp.region', '=', $region_name)
                                        // ->whereIn('kp.province', $prov)
                                        // ->whereBetween('kp.date_uploaded', ['DATE_SUB('.Carbon::now('GMT+8').', INTERVAL 7 DAY)', 'DAY('.Carbon::now('GMT+8').') - 1'])
                                        // ->where('kp.date_uploaded', 'DATE_SUB(CURDATE(), INTERVAL 7 DAY)')
                                        ->where('kp.date_uploaded', '>=', 'DATE(NOW()) - INTERVAL 7 DAY')
                                        ->groupBy(DB::raw('MONTH(kp.date_uploaded)'), DB::raw('DAY(kp.date_uploaded)'), DB::raw('YEAR(kp.date_uploaded)'))
                                        ->orderBy('kp.date_uploaded');
                                }else{
                                    $query
                                        ->whereRaw('kp.date_uploaded BETWEEN DATE_SUB(CURDATE(), INTERVAL 7 DAY) AND CURDATE()')
                                        ->groupBy(DB::raw('MONTH(kp.date_uploaded)'), DB::raw('DAY(kp.date_uploaded)'), DB::raw('YEAR(kp.date_uploaded)'))
                                        ->orderBy('kp.date_uploaded');
                                }
                            })
                            ->get();
        }

        return $query; 

    }

    public function get_by_last_30_days($program_ids){
        $region = session()->get('region');

        $region_name = session()->get('reg_reg');
       
        $provinces_on_region = session()->get('provinces_on_region');

        $prov = [];
        foreach($provinces_on_region as $key => $val){
            foreach($val as $v){
                array_push($prov, $v);
            }
        }
       
        foreach($region_name as $row){
            $region_name = $row->reg_name;
            
            $query = DB::table('kyc_profiles as kp')
                            ->select(DB::raw('DATE_FORMAT(kp.date_uploaded, "%M-%d-%Y") as last_30_days'), 
                                DB::raw('COUNT(kp.kyc_id) as no_of_uploads_kyc'),
                                DB::raw('COUNT(case when ee.kyc_id is not null then 1 end) as no_of_disbursed'),
                                DB::raw('SUM(ifnull(ee.amount, 0)) as total_disbursed_amount'))
                            ->leftJoin('excel_export as ee', 'kp.kyc_id', 'ee.kyc_id')
                            ->when($region, function($query, $region) use($region_name, $prov, $program_ids){
                                if($region != 13){
                                    $query
                                        ->where('kp.region', '=', $region_name)
                                        // ->whereIn('kp.province', $prov)
                                        ->whereRaw('kp.date_uploaded >=  DATE(NOW() - INTERVAL 1 MONTH)')
                                        ->groupBy(DB::raw('MONTH(kp.date_uploaded)'), DB::raw('DAY(kp.date_uploaded)'), DB::raw('YEAR(kp.date_uploaded)'))
                                        ->orderBy('kp.date_uploaded');
                                }else{
                                    $query
                                        ->whereRaw('kp.date_uploaded >=  DATE(NOW() - INTERVAL 1 MONTH)')
                                        ->groupBy(DB::raw('MONTH(kp.date_uploaded)'), DB::raw('DAY(kp.date_uploaded)'), DB::raw('YEAR(kp.date_uploaded)'))
                                        ->orderBy('kp.date_uploaded');
                                }
                            })
                            ->get();
        }

        return $query; 
    }

    public function get_by_this_month($program_ids){
        $region = session()->get('region');

        $region_name = session()->get('reg_reg');
       
        $provinces_on_region = session()->get('provinces_on_region');

        $prov = [];
        foreach($provinces_on_region as $key => $val){
            foreach($val as $v){
                array_push($prov, $v);
            }
        }
       
        foreach($region_name as $row){
            $region_name = $row->reg_name;
            
            $query = DB::table('kyc_profiles as kp')
                            ->select(DB::raw('DATE_FORMAT(kp.date_uploaded, "%M-%d-%Y") as this_month'), 
                                DB::raw('COUNT(kp.kyc_id) as no_of_uploads_kyc'),
                                DB::raw('COUNT(case when ee.kyc_id is not null then 1 end) as no_of_disbursed'),
                                DB::raw('SUM(ifnull(ee.amount, 0)) as total_disbursed_amount'))
                            ->leftJoin('excel_export as ee', 'kp.kyc_id', 'ee.kyc_id')
                            ->when($region, function($query, $region) use($region_name, $prov, $program_ids){
                                if($region != 13){
                                    $query
                                        ->where('kp.region', '=', $region_name)
                                        ->whereIn('kp.province', $prov)
                                        ->whereRaw('MONTH(kp.date_uploaded) = MONTH(NOW())')
                                        ->groupBy('kp.region', DB::raw('MONTH(kp.date_uploaded)'), DB::raw('DAY(kp.date_uploaded)'), DB::raw('YEAR(kp.date_uploaded)'))
                                        ->orderBy('kp.date_uploaded');
                                }else{
                                    $query
                                        ->whereRaw('MONTH(kp.date_uploaded) = MONTH(NOW())')
                                        ->groupBy(DB::raw('MONTH(kp.date_uploaded)'), DB::raw('DAY(kp.date_uploaded)'), DB::raw('YEAR(kp.date_uploaded)'))
                                        ->orderBy('kp.date_uploaded');
                                }
                            })
                            ->get();
        }

        return $query;
    }

    public function get_by_last_month($program_ids){
        $region = session()->get('region');

        $region_name = session()->get('reg_reg');
       
        $provinces_on_region = session()->get('provinces_on_region');

        $prov = [];
        foreach($provinces_on_region as $key => $val){
            foreach($val as $v){
                array_push($prov, $v);
            }
        }
       
        foreach($region_name as $row){
            $region_name = $row->reg_name;
            
            $query = DB::table('kyc_profiles as kp')
                            ->select(DB::raw('DATE_FORMAT(kp.date_uploaded, "%M-%d-%Y") as last_month'), 
                                DB::raw('COUNT(kp.kyc_id) as no_of_uploads_kyc'),
                                DB::raw('COUNT(case when ee.kyc_id is not null then 1 end) as no_of_disbursed'),
                                DB::raw('SUM(ifnull(ee.amount, 0)) as total_disbursed_amount'))
                            ->leftJoin('excel_export as ee', 'kp.kyc_id', 'ee.kyc_id')
                            ->when($region, function($query, $region) use($region_name, $prov, $program_ids){
                                if($region != 13){
                                    $query
                                        ->where('kp.region', '=', $region_name)
                                        // ->whereIn('kp.province', $prov)
                                        ->whereRaw('MONTH(kp.date_uploaded) =  MONTH(NOW()) - 1')
                                        ->groupBy(DB::raw('MONTH(kp.date_uploaded)'), DB::raw('DAY(kp.date_uploaded)'), DB::raw('YEAR(kp.date_uploaded)'))
                                        ->orderBy('kp.date_uploaded'); 
                                }else{
                                    $query
                                        ->whereRaw('MONTH(kp.date_uploaded) =  MONTH(NOW()) - 1')
                                        ->groupBy(DB::raw('MONTH(kp.date_uploaded)'), DB::raw('DAY(kp.date_uploaded)'), DB::raw('YEAR(kp.date_uploaded)'))
                                        ->orderBy('kp.date_uploaded');
                                }
                            })
                            ->get();
        }

        return $query; 
    }

    public function report_region_by_approval_level($program_ids){
        $region = session()->get('region');

        $region_name = session()->get('reg_reg');

        $get_all_region = DB::table('kyc_profiles')
                                ->select('region')
                                ->groupBy('region')
                                ->get();

        $kyc_region = [];
        foreach($get_all_region as $val){
            array_push($kyc_region, $val->region);
        }

        foreach($region_name as $row){
            $region_name = $row->reg_name;
            
            $query = DB::table('kyc_profiles as kp')
                                ->select('kp.region', 'kp.fintech_provider',
                                        DB::raw('COUNT(kp.kyc_id) as no_of_uploads_kyc'),
                                        DB::raw('COUNT(case when kp.isapproved = 1 then 0 end) as generate_beneficiaries'), 
                                        DB::raw('COUNT(case when kp.approved_by_b = 1 then 0 end ) as approve_by_budget'),
                                        DB::raw('COUNT(case when kp.approved_by_d = 1 then 0 end ) as approve_by_disburse'))
                                        // DB::raw('COUNT() as final_approve'),
                                        // DB::raw('COUNT(case when ee.kyc_id is not null then 1 end) as no_of_disbursed'),
                                        // DB::raw('SUM(ifnull(ee.amount, 0)) as total_disbursed_amount'))
                                ->leftJoin('excel_export as ee', 'kp.kyc_id', 'ee.kyc_id')
                                // ->leftJoin('dbp_batch as dbp', '', '')
                                ->when($region, function($query, $region) use($region_name, $program_ids){
                                    if($region != 13){
                                        $query->where('kp.region', '=', $region_name)
                                            //   ->whereIn('ee.program_id', $program_ids)
                                            ->groupBy('kp.region')
                                            ->orderBY('kp.region');
                                    }
                                    else{
                                        $query
                                            //   ->whereIn('ee.program_id', $program_ids)
                                              ->groupBy('kp.region')
                                              ->orderBY('kp.region');
                                    }
                                })
                                ->get();
        }
        return $query;
    }

    // total upload from kyc
    // generate beneficiaries from excel export, (isapprove -> column)
    // budget from excel export (approve_date_by_b -> column)
    // disbursement (approve_date_by_d -> column)
    // final_approval approve 
    public function report_by_approval_level($program_ids){
        $region = session()->get('region');

        $region_name = session()->get('reg_reg');
        
        $provinces_on_region = session()->get('provinces_on_region');
        
        $prov = [];
        foreach($provinces_on_region as $key => $val){
            foreach($val as $v){
                array_push($prov, $v);
            }
        }

        $get_all_province = DB::table('kyc_profiles')
                                    ->select('province')
                                    ->groupBy('province')
                                    ->get();
              
        $kyc_province = [];
        foreach($get_all_province as  $val){
            array_push($kyc_province, $val->province);
        }

        $get_all_region = DB::table('kyc_profiles')
                                    ->select('region')
                                    ->groupBy('region')
                                    ->get();

        $kyc_region = [];
        foreach($get_all_region as $val){
            array_push($kyc_region, $val->region);
        }
        
        foreach($region_name as $row){
            $region_name = $row->reg_name;
            
            $query = DB::table('kyc_profiles as kp')
                                ->select('kp.region','kp.province', 'kp.fintech_provider',
                                        DB::raw('COUNT(kp.kyc_id) as no_of_uploads_kyc'),
                                        DB::raw('COUNT(case when kp.isapproved = 1 then 0 end) as generate_beneficiaries'), 
                                        DB::raw('COUNT(case when kp.approved_by_b = 1 then 0 end ) as approve_by_budget'),
                                        DB::raw('COUNT(case when kp.approved_by_d = 1 then 0 end ) as approve_by_disburse'))
                                        // DB::raw('COUNT() as final_approve'),
                                        // DB::raw('COUNT(case when ee.kyc_id is not null then 1 end) as no_of_disbursed'),
                                        // DB::raw('SUM(ifnull(ee.amount, 0)) as total_disbursed_amount'))
                                ->leftJoin('excel_export as ee', 'kp.kyc_id', 'ee.kyc_id')
                                // ->leftJoin('dbp_batch as dbp', '', '')
                                ->when($region, function($query, $region) use($region_name, $prov, $program_ids){
                                    if($region != 13){
                                        $query->where('kp.region', '=', $region_name)
                                              ->whereIn('kp.province', $prov)
                                            //   ->whereIn('ee.program_id', $program_ids)
                                              ->groupBy('kp.region', 'kp.province')
                                              ->orderBY('kp.region');
                                    }
                                    else{
                                        $query
                                            //   ->whereIn('ee.program_id', $program_ids)
                                              ->groupBy('kp.region', 'kp.province')
                                              ->orderBY('kp.region')
                                              ->orderBY('kp.province');
                                    }
                                })
                                ->get();
        }
        return $query;
    }
}
