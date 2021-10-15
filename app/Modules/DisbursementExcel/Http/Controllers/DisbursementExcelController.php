<?php

namespace App\Modules\DisbursementExcel\Http\Controllers;

use Carbon\Carbon;
use Ramsey\Uuid\Uuid;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\Facades\DataTables;

class DisbursementExcelController extends Controller
{

    /**
     * Display the module welcome screen
     *
     * @return \Illuminate\Http\Response
     */

    public function index(){
        if (!empty(session('user_id'))) {
            return view("DisbursementExcel::index");
        }else{
            return redirect('/login');
        }
    }

    public function getDisbursementListExcel(Request $request){
        try {
            $session_reg_code = sprintf("%02d", session('reg_code'));
            if ($request->ajax()) {
                $getData = DB::table('kyc_profiles as kyc')
                        ->select(DB::raw("kyc.kyc_id,kyc.dbp_batch_id,kyc.data_source,kyc.fintech_provider,kyc.rsbsa_no,kyc.first_name,kyc.middle_name,kyc.last_name,kyc.ext_name,
                        kyc.id_number,kyc.gov_id_type,kyc.street_purok,kyc.bgy_code,kyc.barangay,kyc.mun_code,kyc.municipality,kyc.district,kyc.prov_code,kyc.province,
                        kyc.reg_code,kyc.region,kyc.birthdate,kyc.place_of_birth,kyc.mobile_no,kyc.sex,kyc.nationality,kyc.profession,kyc.sourceoffunds,kyc.mothers_maiden_name,
                        kyc.no_parcel,kyc.total_farm_area,kyc.account_number,kyc.remarks,kyc.uploaded_by_user_id,kyc.uploaded_by_user_fullname,kyc.date_uploaded,".session("disburse_amount")." as amount"))
                        ->where('kyc.reg_code',$session_reg_code)   
                        ->where('kyc.prov_code',$request->selected_prv_code)                
                        ->where(function ($query){
                            if(session('role_id') == 8){
                                $query->where('kyc.isapproved','1')
                                ->where('kyc.approved_by_b','0');
                                }
                                else if(session('role_id') == 10){
                                $query->where('kyc.isapproved','1')
                                ->where('kyc.approved_by_b','1')
                                ->where('kyc.approved_by_d','0');
                                }
                                else if(session('role_id') == 4){
                                $query->where('kyc.isapproved','0');
                                }
                        })
                        ->get();           
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
