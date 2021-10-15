<?php

namespace App\Modules\SummaryMonitoring\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Modules\SummaryMonitoring\Models\SummaryMonitoring;

class SummaryMonitoringController extends Controller
{

    /**
     * Display the module welcome screen
     *
     * @return \Illuminate\Http\Response
     */
    public function __construct(){
       # parent::__construct();
        $this->monitoring_model = new SummaryMonitoring;
    }

    public function index(){
    	return $this->welcome();
    }

    public function welcome(){
        return view("SummaryMonitoring::monitoring");
    }

    public function get_funds_liquidation(Request $request){
        $date_start = $request->date_start;
        $date_end   = $request->date_end;

    	$result = $this->monitoring_model->get_funds_liquidation($date_start, $date_end);
    	return $result;
    }

    public function get_pie_data(Request $request){
        $date_start = $request->date_start;
        $date_end   = $request->date_end;

        $result = $this->monitoring_model->get_pie_data($date_start, $date_end);
        return $result;
    }

    public function get_upload_disbursed_data(Request $request){
    	$has_special = $request->has_special;
        $date_start  = $request->date_start;
        $date_end    = $request->date_end;

    	$result = $this->monitoring_model->get_upload_disbursed_data($has_special, $date_start, $date_end);
    	return $result;
    }

    public function get_rfo_transactions_data(Request $request){
        $date_start = $request->date_start;
        $date_end   = $request->date_end;

        $result = $this->monitoring_model->get_rfo_transactions_data($date_start, $date_end);
        return $result;
    }
}
