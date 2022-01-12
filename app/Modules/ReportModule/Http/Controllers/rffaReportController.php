<?php

namespace App\Modules\ReportModule\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Yajra\DataTables\Facades\DataTables;
use App\Modules\ReportModule\Models\RffaReportModel;
use App\Models\QueryRepetitiveUseModel;

class rffaReportController extends Controller
{
    public function __construct(Request $request)
    {
        $this->rffa_report_Model = new RffaReportModel;

        $this->RepetitiveQuery = new QueryRepetitiveUseModel;

        $this->middleware('session.module');
    }

    // ==============================================================================  FINTECH FILES REPORT ================================================================================================ 

    // FINTECH FILES TODAY
    public function fintech_files_today(Request $request){
        $fin_start_date = $request->get('fin_start_date');
        $fin_end_date = $request->get('fin_end_date');

        if($request->ajax()){
            return DataTables::of($this->rffa_report_Model->get_fintech_files_by_today($request->get('programs_ids'), $fin_start_date, $fin_end_date))
            ->addColumn('fintech', function($row){
                if($row->fintech == "SPTI")
                    return $return = '<p>SPTI</p>';
                if($row->fintech == "UMSI")
                    return $return = '<p>USSC</p>';
            })
            ->rawColumns(['fintech'])
            ->make(true);
        }
        return view("ReportModule::rcef_rffa_reports");
    }

    // FINTECH FILES YESTERDAY
    public function fintech_files_yesterday(Request $request){
        $fin_start_date = $request->get('fin_start_date');
        $fin_end_date = $request->get('fin_end_date');

        if($request->ajax()){
            return DataTables::of($this->rffa_report_Model->get_fintech_files_by_yesterday($request->get('programs_ids'), $fin_start_date, $fin_end_date))
            ->addColumn('fintech', function($row){
                if($row->fintech == "SPTI")
                    return $return = '<p>SPTI</p>';
                if($row->fintech == "UMSI")
                    return $return = '<p>USSC</p>';
            })
            ->rawColumns(['fintech'])
            ->make(true);
        }
        return view("ReportModule::rcef_rffa_reports");

    }

    // FINTECH FILES LAST 7 DAYS
    public function fintech_files_last_7_days(Request $request){
        $fin_start_date = $request->get('fin_start_date');
        $fin_end_date = $request->get('fin_end_date');

        if($request->ajax()){
            return DataTables::of($this->rffa_report_Model->get_fintech_files_by_last_7_days($request->get('programs_ids'), $fin_start_date, $fin_end_date))
            ->addColumn('fintech', function($row){
                if($row->fintech == "SPTI")
                    return $return = '<p>SPTI</p>';
                if($row->fintech == "UMSI")
                    return $return = '<p>USSC</p>';
            })
            ->rawColumns(['fintech'])
            ->make(true);
        }
        return view("ReportModule::rcef_rffa_reports");

    }

    // FINTECH FILES LAST 30 DAYS
    public function fintech_files_last_30_days(Request $request){
        $fin_start_date = $request->get('fin_start_date');
        $fin_end_date = $request->get('fin_end_date');

        if($request->ajax()){
            return DataTables::of($this->rffa_report_Model->get_fintech_files_by_last_30_days($request->get('programs_ids'), $fin_start_date, $fin_end_date))
            ->addColumn('fintech', function($row){
                if($row->fintech == "SPTI")
                    return $return = '<p>SPTI</p>';
                if($row->fintech == "UMSI")
                    return $return = '<p>USSC</p>';
            })
            ->rawColumns(['fintech'])
            ->make(true);
        }
        return view("ReportModule::rcef_rffa_reports");

    }

    public function fintech_files_this_month_pt02(Request $request){
        if($request->ajax()){
            return DataTables::of($this->rffa_report_Model->get_fintech_files_by_this_month_02($request->get('programs_ids')))
            ->addColumn('fintech', function($row){
                if($row->fintech == "SPTI")
                    return $return = '<p>SPTI</p>';
                if($row->fintech == "UMSI")
                    return $return = '<p>USSC</p>';
            })
            ->rawColumns(['fintech'])
            ->make(true);
        }
        return view("ReportModule::rcef_rffa_reports");
    }
    
    // FINTECH FILES THIS MONTH
    public function fintech_files_this_month(Request $request){
        $fin_start_date = $request->get('fin_start_date');
        $fin_end_date = $request->get('fin_end_date');

        if($request->ajax()){
            return DataTables::of($this->rffa_report_Model->get_fintech_files_by_this_month($request->get('programs_ids'), $fin_start_date, $fin_end_date))
            ->addColumn('fintech', function($row){
                if($row->fintech == "SPTI")
                    return $return = '<p>SPTI</p>';
                if($row->fintech == "UMSI")
                    return $return = '<p>USSC</p>';
            })
            ->rawColumns(['fintech'])
            ->make(true);
        }
        return view("ReportModule::rcef_rffa_reports");
    }

    // FINTECH FILES LAST MONTH
    public function fintech_files_last_month(Request $request){
        $fin_start_date = $request->get('fin_start_date');
        $fin_end_date = $request->get('fin_end_date');

        if($request->ajax()){
            return DataTables::of($this->rffa_report_Model->get_fintech_files_by_last_month($request->get('programs_ids'), $fin_start_date, $fin_end_date))
            ->addColumn('fintech', function($row){
                if($row->fintech == "SPTI")
                    return $return = '<p>SPTI</p>';
                if($row->fintech == "UMSI")
                    return $return = '<p>USSC</p>';
            })
            ->rawColumns(['fintech'])
            ->make(true);
        }
        return view("ReportModule::rcef_rffa_reports");

    }

    // FINTECH FILES CUSTOM RANGE
    public function fintech_files_custom_range(Request $request){

        $fin_start_date = $request->get('fin_start_date');
        $fin_end_date = $request->get('fin_end_date');

        if($request->ajax()){
            return DataTables::of($this->rffa_report_Model->fintech_no_uploaded_file_and_no_disbursement_file_custom_range($request->get('programs_ids'), $fin_start_date, $fin_end_date))
            // return DataTables::of($this->rffa_report_Model->fintech_no_uploaded_file_and_no_disbursement_file())
            ->addColumn('fintech', function($row){
                if($row->fintech == "SPTI")
                    return $return = '<p>SPTI</p>';
                if($row->fintech == "UMSI")
                    return $return = '<p>USSC</p>';
            })
            ->rawColumns(['fintech'])
            ->make(true);
        }
        return view("ReportModule::rcef_rffa_reports");

    }

    // ==============================================================================  FINTECH RECORDS REPORT ================================================================================================ 

    // FINTECH RECORDS TODAY
    public function fintech_records_today(Request $request){
        $fin_start_date = $request->get('fin_start_date');
        $fin_end_date = $request->get('fin_end_date');

        if($request->ajax()){
            return DataTables::of($this->rffa_report_Model->get_fintech_records_by_today($request->get('programs_ids'), $fin_start_date, $fin_end_date))
            ->addColumn('fintech', function($row){
                if($row->fintech == "SPTI")
                    return $return = '<p>SPTI</p>';
                if($row->fintech == "UMSI")
                    return $return = '<p>USSC</p>';
            })
            ->rawColumns(['fintech'])
            ->make(true);
        }

        return view("ReportModule::rcef_rffa_reports");

    }

    // FINTECH RECORDS YESTERDAY
    public function fintech_records_yesterday(Request $request){
        $fin_start_date = $request->get('fin_start_date');
        $fin_end_date = $request->get('fin_end_date');

        if($request->ajax()){
            return DataTables::of($this->rffa_report_Model->get_fintech_records_by_yesterday($request->get('programs_ids'), $fin_start_date, $fin_end_date))
            ->make(true);
        }

        return view("ReportModule::rcef_rffa_reports");

    }

    // FINTECH RECORDS LAST 7 DAYS
    public function fintech_records_last_7_days(Request $request){
        $fin_start_date = $request->get('fin_start_date');
        $fin_end_date = $request->get('fin_end_date');

        if($request->ajax()){
            return DataTables::of($this->rffa_report_Model->get_fintech_records_by_last_7_days($request->get('programs_ids'), $fin_start_date, $fin_end_date))
            ->addColumn('fintech', function($row){
                if($row->fintech == "SPTI")
                    return $return = '<p>SPTI</p>';
                if($row->fintech == "UMSI")
                    return $return = '<p>USSC</p>';
            })
            ->rawColumns(['fintech'])
            ->make(true);
        }

        return view("ReportModule::rcef_rffa_reports");

    }

    // FINTECH RECORDS LAST 30 DAYS
    public function fintech_records_last_30_days(Request $request){
        $fin_start_date = $request->get('fin_start_date');
        $fin_end_date = $request->get('fin_end_date');

        if($request->ajax()){
            return DataTables::of($this->rffa_report_Model->get_fintech_records_by_last_30_days($request->get('programs_ids'), $fin_start_date, $fin_end_date))
            ->addColumn('fintech', function($row){
                if($row->fintech == "SPTI")
                    return $return = '<p>SPTI</p>';
                if($row->fintech == "UMSI")
                    return $return = '<p>USSC</p>';
            })
            ->rawColumns(['fintech'])
            ->make(true);
        }

        return view("ReportModule::rcef_rffa_reports");

    }

    // FINTECH RECORDS DEFAULT SELECTED FILTER BY THIS MONTH
    public function fintech_records_this_month_pt02(Request $request){
        $fin_start_date = $request->get('fin_start_date');
        $fin_end_date = $request->get('fin_end_date');

        if($request->ajax()){
            return DataTables::of($this->rffa_report_Model->get_fintech_records_by_this_month_02($request->get('programs_ids'), $fin_start_date, $fin_end_date))
            ->addColumn('fintech', function($row){
                if($row->fintech == "SPTI")
                    return $return = '<p>SPTI</p>';
                if($row->fintech == "UMSI")
                    return $return = '<p>USSC</p>';
            })
            ->rawColumns(['fintech'])
            ->make(true);
        }

        return view("ReportModule::rcef_rffa_reports");

    }

    // FINTECH RECORDS THIS MONTH
    public function fintech_records_this_month(Request $request){
        $fin_start_date = $request->get('fin_start_date');
        $fin_end_date = $request->get('fin_end_date');

        if($request->ajax()){
            return DataTables::of($this->rffa_report_Model->get_fintech_records_by_this_month($request->get('programs_ids'), $fin_start_date, $fin_end_date))
            ->addColumn('fintech', function($row){
                if($row->fintech == "SPTI")
                    return $return = '<p>SPTI</p>';
                if($row->fintech == "UMSI")
                    return $return = '<p>USSC</p>';
            })
            ->rawColumns(['fintech'])
            ->make(true);
        }

        return view("ReportModule::rcef_rffa_reports");

    }

    // FINTECH RECORDS LAST MONTH
    public function fintech_records_last_month(Request $request){
        $fin_start_date = $request->get('fin_start_date');
        $fin_end_date = $request->get('fin_end_date');

        if($request->ajax()){
            return DataTables::of($this->rffa_report_Model->get_fintech_records_by_last_month($request->get('programs_ids'), $fin_start_date, $fin_end_date))
            ->addColumn('fintech', function($row){
                if($row->fintech == "SPTI")
                    return $return = '<p>SPTI</p>';
                if($row->fintech == "UMSI")
                    return $return = '<p>USSC</p>';
            })
            ->rawColumns(['fintech'])
            ->make(true);
        }

        return view("ReportModule::rcef_rffa_reports");

    }

    // FINTECH RECORDS CUSTOM RANGE
    public function fintech_records_custom_range(Request $request){

        $fin_start_date = $request->get('fin_start_date');
        $fin_end_date = $request->get('fin_end_date');

        if($request->ajax()){
            return DataTables::of($this->rffa_report_Model->fintech_no_records_uploaded_and_no_records_disbursed($request->get('programs_ids'), $fin_start_date, $fin_end_date))
            ->addColumn('fintech', function($row){
                if($row->fintech == "SPTI")
                    return $return = '<p>SPTI</p>';
                if($row->fintech == "UMSI")
                    return $return = '<p>USSC</p>';
            })
            ->rawColumns(['fintech'])
            ->make(true);
        }

        return view("ReportModule::rcef_rffa_reports");

    }

    // ==============================================================================  DATA REPORT ================================================================================================  

    public function rfo_or_co_program_focal_summary(Request $request){

        $region = $this->RepetitiveQuery->get_region_on_geo_map();

        $province = $this->RepetitiveQuery->get_province_on_geo_map();

        $municipality = $this->RepetitiveQuery->get_municipality_on_geo_map();

        if($request->ajax()){
            return DataTables::of($this->rffa_report_Model->get_by_rfo_or_co_progam_focal_summary($request->get('programs_ids')))
            ->make(true);
        }
        
        return view("ReportModule::rcef_rffa_reports", ['region' => $region, 'province' => $province, 'municipality' => $municipality]);

    }

    public function co_program_focal_summary_by_region(Request $request){

        $region = $this->RepetitiveQuery->get_region_on_geo_map();

        $province = $this->RepetitiveQuery->get_province_on_geo_map();

        if($request->ajax()){
            return DataTables::of($this->rffa_report_Model->get_by_region_co_program_focal_summary($request->get('programs_ids')))
            ->make(true);
        }
        return view("ReportModule::rcef_rffa_reports", ['region' => $region, 'province' => $province]);

    }

    public function co_program_focal_summary_by_region_province_municipality_and_barangay(Request $request){

        $region = $this->RepetitiveQuery->get_region_on_geo_map();

        $province = $this->RepetitiveQuery->get_province_on_geo_map();

        if($request->ajax()){
            return DataTables::of($this->rffa_report_Model->get_by_region_province_municipality_and_barangay_co_program_focal_summary($request->get('programs_ids')))
            ->make(true);
        }
        return view("ReportModule::rcef_rffa_reports", ['region' => $region, 'province' => $province]);

    }

    public function custom_range(Request $request){

        $start_date = $request->get('start_date');
        $end_date = $request->get('end_date');

        if($request->ajax()){
            return DataTables::of($this->rffa_report_Model->get_by_custom_range($request->get('programs_ids'), $start_date, $end_date))
            ->make(true);
        }
        return view("ReportModule::rcef_rffa_reports");
        
    }

    public function monthly(Request $request){

        if($request->ajax()){
            return DataTables::of($this->rffa_report_Model->get_by_monthly($request->get('programs_ids')))
            ->make(true);
        }
        return view("ReportModule::rcef_rffa_reports");
    }

    public function today(Request $request){

        if($request->ajax()){
            return DataTables::of($this->rffa_report_Model->get_by_today($request->get('programs_ids')))
            ->make(true);
        }
        return view("ReportModule::rcef_rffa_reports");
    }

    public function yesterday(Request $request){
        
        if($request->ajax()){
            return DataTables::of($this->rffa_report_Model->get_by_yesterday($request->get('programs_ids')))
            ->make(true);
        }
        return view("ReportModule::rcef_rffa_reports");
    }

    public function last_7_days(Request $request){
        
        if($request->ajax()){
            return DataTables::of($this->rffa_report_Model->get_by_last_7_days($request->get('programs_ids')))
            ->make(true);
        }
        return view("ReportModule::rcef_rffa_reports");
    }

    public function last_30_days(Request $request){
        
        if($request->ajax()){
            return DataTables::of($this->rffa_report_Model->get_by_last_30_days($request->get('programs_ids')))
            ->make(true);
        }
        return view("ReportModule::rcef_rffa_reports");
    }

    public function this_month(Request $request){
        
        if($request->ajax()){
            return DataTables::of($this->rffa_report_Model->get_by_this_month($request->get('programs_ids')))
            ->make(true);
        }
        return view("ReportModule::rcef_rffa_reports");
    }

    public function last_month(Request $request){
        
        if($request->ajax()){
            return DataTables::of($this->rffa_report_Model->get_by_last_month($request->get('programs_ids')))
            ->make(true);
        }
        return view("ReportModule::rcef_rffa_reports");
    }
    
    public function by_regional_approval(Request $request){

        if($request->ajax()){
            return DataTables::of($this->rffa_report_Model->report_region_by_approval_level($request->get('programs_ids')))
            ->make(true);
        }

        return view("ReportModule::rcef_rffa_reports");

    }

    public function by_approval(Request $request){

        if($request->ajax()){
            return DataTables::of($this->rffa_report_Model->report_by_approval_level($request->get('programs_ids')))
            ->make(true);
        }
        return view("ReportModule::rcef_rffa_reports");

    }
}
