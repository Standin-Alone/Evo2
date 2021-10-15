<?php

namespace App\Modules\ReportModule\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Yajra\DataTables\Facades\DataTables;
use App\Modules\ReportModule\Models\RffaReportModel;

class rffaReportController extends Controller
{
    public function __construct(Request $request)
    {
        $this->rffa_report_Model = new RffaReportModel;

        $this->middleware('session.module');
    }

    public function rfo_or_co_program_focal_summary(Request $request){
        if($request->ajax()){
            return DataTables::of($this->rffa_report_Model->get_by_rfo_or_co_progam_focal_summary($request->get('programs_ids')))
            ->make(true);
        }
        
        return view("ReportModule::rcef_rffa_reports");
    }

    public function co_program_focal_summary_by_region(Request $request){

        if($request->ajax()){
            return DataTables::of($this->rffa_report_Model->get_by_region_co_program_focal_summary($request->get('programs_ids')))->make(true);
        }
        return view("ReportModule::rcef_rffa_reports");
    }

    public function co_program_focal_summary_by_region_province_municipality_and_barangay(Request $request){
        if($request->ajax()){
            return DataTables::of($this->rffa_report_Model->get_by_region_province_municipality_and_barangay_co_program_focal_summary($request->get('programs_ids')))->make(true);
        }
        return view("ReportModule::rcef_rffa_reports");
    }

    public function custom_range(Request $request){
        $start_date = $request->get('start_date');
        $end_date = $request->get('end_date');

        if($request->ajax()){
            return DataTables::of($this->rffa_report_Model->get_by_custom_range($request->get('programs_ids'), $start_date, $end_date))->make(true);
        }
        return view("ReportModule::rcef_rffa_reports");
    }

    public function monthly(Request $request){

        if($request->ajax()){
            return DataTables::of($this->rffa_report_Model->get_by_monthly($request->get('programs_ids')))->make(true);
        }
        return view("ReportModule::rcef_rffa_reports");
    }

    public function today(Request $request){

        if($request->ajax()){
            return DataTables::of($this->rffa_report_Model->get_by_today($request->get('programs_ids')))->make(true);
        }
        return view("ReportModule::rcef_rffa_reports");
    }

    public function yesterday(Request $request){
        
        if($request->ajax()){
            return DataTables::of($this->rffa_report_Model->get_by_yesterday($request->get('programs_ids')))->make(true);
        }
        return view("ReportModule::rcef_rffa_reports");
    }

    public function last_7_days(Request $request){
        
        if($request->ajax()){
            return DataTables::of($this->rffa_report_Model->get_by_last_7_days($request->get('programs_ids')))->make(true);
        }
        return view("ReportModule::rcef_rffa_reports");
    }

    public function last_30_days(Request $request){
        
        if($request->ajax()){
            return DataTables::of($this->rffa_report_Model->get_by_last_30_days($request->get('programs_ids')))->make(true);
        }
        return view("ReportModule::rcef_rffa_reports");
    }

    public function this_month(Request $request){
        
        if($request->ajax()){
            return DataTables::of($this->rffa_report_Model->get_by_this_month($request->get('programs_ids')))->make(true);
        }
        return view("ReportModule::rcef_rffa_reports");
    }

    public function last_month(Request $request){
        
        if($request->ajax()){
            return DataTables::of($this->rffa_report_Model->get_by_last_month($request->get('programs_ids')))->make(true);
        }
        return view("ReportModule::rcef_rffa_reports");
    }

    public function by_approval(Request $request){

        if($request->ajax()){
            return DataTables::of($this->rffa_report_Model->report_by_approval_level($request->get('programs_ids')))->make(true);
        }
        return view("ReportModule::rcef_rffa_reports");
    }
}
