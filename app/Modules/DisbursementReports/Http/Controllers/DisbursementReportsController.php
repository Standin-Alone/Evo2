<?php

namespace App\Modules\DisbursementReports\Http\Controllers;

use Carbon\Carbon;
use Ramsey\Uuid\Uuid;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\Facades\DataTables;

class DisbursementReportsController extends Controller
{

    /**
     * Display the module welcome screen
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (!empty(session('user_id'))) {
            return view("DisbursementReports::index");
        }else{
            return redirect('/login');
        }
    }

    public function getDisbursementListReports_overall(Request $request){
        try {
            $session_reg_code = sprintf("%02d", session('reg_code'));        
            if ($request->ajax()) {
                $getData = "";
                if(!empty($request->from_date) || !empty($request->from_date)){
                    $getData = DB::table('kyc_profiles as kyc')
                        ->select(DB::raw("kyc.kyc_id,kyc.dbp_batch_id,kyc.rsbsa_no,concat(kyc.last_name,' ,',kyc.first_name,' ', ifnull(kyc.middle_name,'')) as fullname,
                            kyc.barangay,kyc.municipality,kyc.prov_code,kyc.province,kyc.reg_code,kyc.region,kyc.birthdate,kyc.mobile_no,kyc.sex,kyc.date_uploaded,".session("disburse_amount")." as amount"))
                        ->where('kyc.reg_code',$session_reg_code)   
                        ->where('kyc.prov_code',$request->selected_prv_code)                
                        ->where('kyc.account_number','<>','')                        
                        ->whereBetween(DB::raw("date(date_uploaded)"), [$request->from_date, $request->to_date])
                        ->get();   
                }else{
                    $getData = DB::table('kyc_profiles as kyc')
                        ->select(DB::raw("kyc.kyc_id,kyc.dbp_batch_id,kyc.rsbsa_no,concat(kyc.last_name,' ,',kyc.first_name,' ', ifnull(kyc.middle_name,'')) as fullname,
                            kyc.barangay,kyc.municipality,kyc.prov_code,kyc.province,kyc.reg_code,kyc.region,kyc.birthdate,kyc.mobile_no,kyc.sex,kyc.date_uploaded,".session("disburse_amount")." as amount"))
                        ->where('kyc.reg_code',$session_reg_code)   
                        ->where('kyc.prov_code',$request->selected_prv_code)                
                        ->where('kyc.account_number','<>','')                        
                        ->get();   
                }                        
                return Datatables::of($getData)->make(true);
            }
        } catch (\Throwable $th) {
            return dd($th);
        }
    } 
    public function getDisbursementListReports_pending(Request $request){
        try {
            $session_reg_code = sprintf("%02d", session('reg_code'));
            $filterby = $request->filterby;
            if ($request->ajax()) {
                $getData = "";
                if(!empty($request->from_date) || !empty($request->from_date)){
                    $getData = DB::table('kyc_profiles as kyc')
                        ->select(DB::raw("kyc.kyc_id,kyc.dbp_batch_id,kyc.rsbsa_no,concat(kyc.last_name,' ,',kyc.first_name,' ', ifnull(kyc.middle_name,'')) as fullname,
                            kyc.barangay,kyc.municipality,kyc.prov_code,kyc.province,kyc.reg_code,kyc.region,kyc.birthdate,kyc.mobile_no,kyc.sex,kyc.date_uploaded,".session("disburse_amount")." as amount"))
                        ->where('kyc.reg_code',$session_reg_code)   
                        ->where('kyc.prov_code',$request->selected_prv_code)                
                        ->where('kyc.approved_by_d','0')
                        ->whereBetween(DB::raw("date(date_uploaded)"), [$request->from_date, $request->to_date])
                        ->get();   
                }else{
                    $getData = DB::table('kyc_profiles as kyc')
                        ->select(DB::raw("kyc.kyc_id,kyc.dbp_batch_id,kyc.rsbsa_no,concat(kyc.last_name,' ,',kyc.first_name,' ', ifnull(kyc.middle_name,'')) as fullname,
                            kyc.barangay,kyc.municipality,kyc.prov_code,kyc.province,kyc.reg_code,kyc.region,kyc.birthdate,kyc.mobile_no,kyc.sex,kyc.date_uploaded,".session("disburse_amount")." as amount"))
                        ->where('kyc.reg_code',$session_reg_code)   
                        ->where('kyc.prov_code',$request->selected_prv_code)                
                        ->where('kyc.approved_by_d','0')
                        ->get();   
                }
                        
                return Datatables::of($getData)->make(true);
            }
        } catch (\Throwable $th) {
            return dd($th);
        }
    }

    public function getDisbursementListReports_approved(Request $request){
        try {
            $session_reg_code = sprintf("%02d", session('reg_code'));
            $filterby = $request->filterby;
            if ($request->ajax()) {
                $getData = "";
                if(!empty($request->from_date) || !empty($request->from_date)){
                    $getData = DB::table('kyc_profiles as kyc')
                        ->select(DB::raw("kyc.kyc_id,kyc.dbp_batch_id,kyc.rsbsa_no,concat(kyc.last_name,' ,',kyc.first_name,' ', ifnull(kyc.middle_name,'')) as fullname,
                            kyc.barangay,kyc.municipality,kyc.prov_code,kyc.province,kyc.reg_code,kyc.region,kyc.birthdate,kyc.mobile_no,kyc.sex,kyc.date_uploaded,".session("disburse_amount")." as amount"))
                        ->where('kyc.reg_code',$session_reg_code)   
                        ->where('kyc.prov_code',$request->selected_prv_code)                
                        ->where('kyc.approved_by_d','1')
                        ->whereBetween(DB::raw("date(date_uploaded)"), [$request->from_date, $request->to_date])
                        ->get();   
                }else{
                    $getData = DB::table('kyc_profiles as kyc')
                        ->select(DB::raw("kyc.kyc_id,kyc.dbp_batch_id,kyc.rsbsa_no,concat(kyc.last_name,' ,',kyc.first_name,' ', ifnull(kyc.middle_name,'')) as fullname,
                            kyc.barangay,kyc.municipality,kyc.prov_code,kyc.province,kyc.reg_code,kyc.region,kyc.birthdate,kyc.mobile_no,kyc.sex,kyc.date_uploaded,".session("disburse_amount")." as amount"))
                        ->where('kyc.reg_code',$session_reg_code)   
                        ->where('kyc.prov_code',$request->selected_prv_code)                
                        ->where('kyc.approved_by_d','1')
                        ->get();   
                }
                        
                return Datatables::of($getData)->make(true);
            }
        } catch (\Throwable $th) {
            return dd($th);
        }
    }

    public function getDisbursementApprovalProvinceList(){
        try {
            $session_reg_code = sprintf("%02d", session('reg_code'));
            $getProvince = DB::table('geo_map as geo')
            ->select(DB::raw("geo.prov_code,geo.prov_name"))
            ->where('geo.reg_code',$session_reg_code)
            ->groupBy('geo.prov_code')
            ->get();
            return response()->json($getProvince);
        } catch (\Throwable $th) {
            return dd($th);
        }
    }
}
