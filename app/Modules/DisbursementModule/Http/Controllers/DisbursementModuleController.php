<?php

namespace App\Modules\DisbursementModule\Http\Controllers;

use Carbon\Carbon;
use Ramsey\Uuid\Uuid;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use App\Models\GlobalNotificationModel;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\Facades\DataTables;
use App\Exports\GenerateBeneficiariesExcel;

class DisbursementModuleController extends Controller
{
    public function __construct(){        
        $this->middleware('session.notifications');
     
    }
    /**
     * Display the module welcome screen
     *
     * @return \Illuminate\Http\Response
     */

            
    public function index()
    {
        if(!empty(session('uuid'))){
            return view("DisbursementModule::index");
        }
        else{
            return redirect('/login');
        }
    }

    public function getDisbursementList(Request $request){       
        $session_reg_code = sprintf("%02d", session('reg_code'));
        $kyc_file_id = $request->kyc_file_id;
        $filterby = $request->filterby;
        $kyc_batch_id = $request->kyc_batch_id;
        $kyc_mun_code = $request->kyc_mun_code;
        $getData = "";
        if ($request->ajax()) {
                $getData = DB::table('kyc_profiles as kyc')
                    ->select(DB::raw("kyc.kyc_id,kyc.dbp_batch_id,kyc.rsbsa_no,concat(kyc.last_name,' ,',kyc.first_name,' ', ifnull(kyc.middle_name,'')) as fullname,kyc.fund_id,
                        kyc.account_number,kyc.barangay,kyc.municipality,kyc.prov_code,kyc.province,kyc.mun_code,kyc.municipality,
                        kyc.reg_code,kyc.region,kyc.birthdate,kyc.mobile_no,kyc.sex,kyc.date_uploaded,".session("disburse_amount")." as amount,kyc.isremove"))
                    ->where('kyc.reg_code',$session_reg_code) 
                    ->where('kyc.kyc_file_id',$kyc_file_id) 
                    ->where('kyc.isremove',$filterby) 
                    // ->where(function ($sql) use ($kyc_mun_code){ 
                    //     if(!empty($kyc_mun_code)){
                    //         $sql->whereIn('kyc.mun_code',$kyc_mun_code);
                    //         }
                    //  })
                    ->where(function ($query) use ($kyc_batch_id){
                        if(session('role_id') == 8){
                        $query->where('kyc.approved_batch_seq',$kyc_batch_id)
                        ->where('kyc.isapproved','1')
                        ->where('kyc.approved_by_b','0');
                        }
                        else if(session('role_id') == 10){
                        $query->where('kyc.approved_batch_seq',$kyc_batch_id)
                        ->where('kyc.isapproved','1')
                        ->where('kyc.approved_by_b','1')
                        ->where('kyc.approved_by_d','0');
                        }
                        else if(session('role_id') == 4){
                            $query->where('kyc.isapproved','0');
                        }else{
                            return dd("Access Denied!");
                        }
                    })
                    ->get();
           
            return Datatables::of($getData)->make(true);
        }     
    }

    public function getEndorseMunList(Request $request){       
        $kyc_batch_id = $request->kyc_batch_id;
        if ($request->ajax()) {
                $getData = DB::table('kyc_profiles as kyc')
                    ->select(DB::raw("kyc.kyc_id,kyc.dbp_batch_id,kyc.rsbsa_no,concat(kyc.last_name,' ,',kyc.first_name,' ', ifnull(kyc.middle_name,'')) as fullname,kyc.fund_id,
                        kyc.account_number,kyc.barangay,kyc.municipality,kyc.prov_code,kyc.province,kyc.mun_code,kyc.municipality,
                        kyc.reg_code,kyc.region,kyc.birthdate,kyc.mobile_no,kyc.sex,kyc.date_uploaded,".session("disburse_amount")." as amount,kyc.isremove"))
                    ->where('kyc.approved_batch_seq',$kyc_batch_id)
                    ->get();           
            return Datatables::of($getData)->make(true);
        }     
    }

    public function removeDisbursementBeneficiaries(Request $request){
        DB::transaction(function() use(&$request){
            DB::table('remove_bene_logs')->insert([
                'trans_id'=> Uuid::uuid4(),
                'kyc_id'=> $request->kyc_id,
                'date_removed'=> Carbon::now('GMT+8'),
                'removed_by_id'=> session('user_id'),
                'removed_by_fullname'=> session('user_fullname'),
                'remarks'=> $request->remarks,
                'status'=> "REMOVED",
            ]);

            DB::table('kyc_profiles')
                ->where('kyc_id',$request->kyc_id)   
                ->update(['isremove' => 1,'date_removed'=>Carbon::now('GMT+8'),
            'removed_by_id'=>session('user_id'),'removed_by_fullname'=>session('user_fullname')]);

            if(session('role_id') == 10){
                return DB::table('fund_source')
                        ->where('fund_id',$request->fund_id) 
                        ->where('total_liquidated','>',0)  
                        ->update(['total_liquidated' => DB::raw('total_liquidated - '.session("disburse_amount"))]);
            } 
        });     
    }

    public function activateDisbursementBeneficiaries(Request $request){
        DB::transaction(function() use(&$request){
            DB::table('remove_bene_logs')->insert([
                'trans_id'=> Uuid::uuid4(),
                'kyc_id'=> $request->kyc_id,
                'date_removed'=> Carbon::now('GMT+8'),
                'removed_by_id'=> session('user_id'),
                'removed_by_fullname'=> session('user_fullname'),
                'remarks'=> "",
                'status'=> "ACTIVATED",
            ]);
            DB::table('kyc_profiles')
                ->where('kyc_id',$request->kyc_id)   
                ->update(['isremove' => 0,'date_removed'=>null,'removed_by_id'=>null,'removed_by_fullname'=>null]);               

            if(session('role_id') == 10){                    
                return DB::table('fund_source')
                    ->where('fund_id',$request->fund_id) 
                    ->update(['total_liquidated' => DB::raw('total_liquidated + '.session("disburse_amount"))]); 
            }
        });      
    }

    public function approveDisbursement(Request $request){
        $CurrentYear = Carbon::now('GMT+8')->format('Y');
        $CurrentProgram = session('Default_Program_shortname');
        $batch_seq = "";     
        $session_reg_code = sprintf("%02d", session('reg_code'));
        $kyc_file_id = $request->kyc_file_id; 
        $selected_fund_source = $request->selected_fund_source;  
        $total_amount = $request->total_amount;
        $filtered_mun = $request->filtered_mun;
        $file_name = $request->kyc_file_name;
        $file_name = str_replace(".xlsx","",$file_name);
        $file_name = str_replace(".xls","",$file_name);
        $file_name_new = str_replace("_v1","",$file_name);
        $file_name_new = substr($file_name_new,6);        
        $file_date = substr($file_name,17,-3);
        $file_province = substr($file_name,10,-15);
        $file_Provider = substr($file_name,13,-11);
        $kyc_prov_code = $request->kyc_prov_code;
        $role_id = session('role_id');
        $message = ""; 
        if($role_id == 8){
            $getRegionDetails = DB::table('kyc_profiles as kyc')
                ->select(DB::raw("kyc.kyc_id,kyc.prov_code,ucase(kyc.province) as province,kyc.date_uploaded,format(count(kyc.kyc_id),0) as total_records,
                format(sum(".session("disburse_amount")."),2) as amount,kyc.approved_batch_seq,kyc.isapproved,kyc.approved_by_b,kyc.approved_by_d,dbp.folder_file_name"))
                ->leftjoin('dbp_batch as dbp','dbp.dbp_batch_id','kyc.dbp_batch_id')
                ->where('kyc.reg_code',$session_reg_code)   
                ->where('kyc.kyc_file_id',$kyc_file_id) 
                ->where('kyc.approved_batch_seq',$request->kyc_batch_id)
                ->where('isapproved','1')
                ->where('approved_by_b','0')
                ->where('isremove','0') 
                ->take(1)
                ->get();

            foreach ($getRegionDetails as $key => $value) {
                $message = 'This Province '.$value->province.' Reviewed by RFO Focal Supervisor with '. 'the total beneficiaries of '.$value->total_records.' and total amount of '.$value->amount.'. ';
                $message = $message.'for more details just click the provided link to direct access the system.';
            }
            
            DB::transaction(function() use (&$session_reg_code,&$request,&$global_model,&$role_id,&$message,&$selected_fund_source,&$total_amount){
                DB::table('kyc_profiles')
                    ->where('reg_code',$session_reg_code)   
                    ->where('kyc_file_id',$request->kyc_file_id) 
                    ->where('approved_batch_seq',$request->kyc_batch_id)
                    ->where('isapproved','1')
                    ->where('approved_by_b','0')
                    ->where('isremove','0') 
                    ->update(['approved_by_b' => 1,'approved_date_b'=>Carbon::now('GMT+8'),'approved_id_b'=>session('user_id'),
                    'fund_id'=>$selected_fund_source]); 
                
                DB::table('fund_source')
                    ->where('fund_id', $selected_fund_source)
                    ->update(['total_liquidated' => DB::raw('total_liquidated + '.$total_amount)]);                        
                
                $global_model = new GlobalNotificationModel;                
                return $global_model->send_email($role_id,$session_reg_code,$message);                        
            }); 
        }
        else if($role_id == 10){
            DB::transaction(function() use(&$session_reg_code,&$request){
                DB::table('kyc_profiles')
                    ->where('reg_code',$session_reg_code)   
                    ->where('kyc_file_id',$request->kyc_file_id) 
                    ->where('approved_batch_seq',$request->kyc_batch_id)
                    ->where('isapproved','1')
                    ->where('approved_by_b','1')
                    ->where('approved_by_d','0')
                    ->where('isremove','0') 
                    ->update(['approved_by_d' => 1,'approved_date_d'=>Carbon::now('GMT+8'),'approved_id_d'=>session('user_id')]);
                });                    
        }
        else if($role_id == 4){            
            if($filtered_mun != NULL){
                $getData = DB::table('kyc_profiles')
                    ->select(DB::raw("(CASE WHEN MAX(RIGHT(concat('000',right(SUBSTR(approved_batch_seq,1,32),3)+1),3)) IS NULL THEN '001'
                    ELSE MAX(RIGHT(concat('000',right(SUBSTR(approved_batch_seq,1,32),3)+1),3)) END) as batch_seq"))
                    ->where('reg_code',$session_reg_code)
                    ->where('prov_code',$kyc_prov_code)
                    ->get();
                foreach ($getData as $key => $value) {
                    // $batch_seq = 'DISINT'.$CurrentProgram.$file_province.$file_Provider.$file_date.$value->batch_seq;
                    $batch_seq = 'DISINT'.$file_name_new.'_'.$value->batch_seq.'_v1';
                }
                $getRegionDetails = DB::table('kyc_profiles as kyc')
                    ->select(DB::raw("kyc.kyc_id,kyc.prov_code,ucase(kyc.province) as province,kyc.date_uploaded,format(count(kyc.kyc_id),0) as total_records,
                    format(sum(".session("disburse_amount")."),2) as amount,kyc.approved_batch_seq,kyc.isapproved,kyc.approved_by_b,kyc.approved_by_d,dbp.folder_file_name"))
                    ->leftjoin('dbp_batch as dbp','dbp.dbp_batch_id','kyc.dbp_batch_id')
                    ->where('kyc.reg_code',$session_reg_code)   
                    ->where('kyc.kyc_file_id',$request->kyc_file_id) 
                    ->whereIn('kyc.mun_code',$filtered_mun)
                    ->where('isapproved','0')
                    ->where('isremove','0') 
                    ->take(1)
                    ->get();
                    foreach ($getRegionDetails as $key => $value) {
                        $message = 'This Province '.$value->province.' Endorsed by RFO Program Focal with '. 'the total beneficiaries of '.$value->total_records.' and total amount of '.$value->amount.'. ';
                        $message = $message.'for more details just click the provided link to direct access the system.';
                    }   

                DB::transaction(function() use (&$session_reg_code,&$request,&$global_model,&$role_id,&$message,&$batch_seq,&$filtered_mun,&$i){
                    DB::table('kyc_profiles')
                        ->where('reg_code',$session_reg_code)   
                        ->where('kyc_file_id',$request->kyc_file_id) 
                        ->whereIn('mun_code',$filtered_mun)
                        ->where('isapproved','0')
                        ->where('isremove','0') 
                        ->update(['isapproved' => 1,'approved_date'=>Carbon::now('GMT+8'),'approved_by_id'=>session('user_id'),
                        'approved_by_fullname'=>session('user_fullname'),'approved_batch_seq'=>$batch_seq]); 

                    $global_model = new GlobalNotificationModel;                
                    return $global_model->send_email($role_id,$session_reg_code,$message);    
                });
            }else{
                $checkbatchfile = DB::table('kyc_profiles')
                    ->where('approved_batch_seq','like','%'. $file_name_new .'%') 
                    ->take(1)
                    ->exists();
                if($checkbatchfile){
                    $getData = DB::table('kyc_profiles')
                        ->select(DB::raw("(CASE WHEN MAX(RIGHT(concat('000',right(SUBSTR(approved_batch_seq,1,32),3)+1),3)) IS NULL THEN '001'
                        ELSE MAX(RIGHT(concat('000',right(SUBSTR(approved_batch_seq,1,32),3)+1),3)) END) as batch_seq"))
                        ->where('reg_code',$session_reg_code)
                        ->where('prov_code',$kyc_prov_code)
                        ->get();
                    foreach ($getData as $key => $value) {
                        $batch_seq = 'DISINT'.$file_name_new.'_'.$value->batch_seq.'_v1';
                    }
                }else{
                    $batch_seq = 'DISINT'.$file_name_new.'_v1';
                }
                
                $getRegionDetails = DB::table('kyc_profiles as kyc')
                    ->select(DB::raw("kyc.kyc_id,kyc.prov_code,ucase(kyc.province) as province,kyc.date_uploaded,format(count(kyc.kyc_id),0) as total_records,
                    format(sum(".session("disburse_amount")."),2) as amount,kyc.approved_batch_seq,kyc.isapproved,kyc.approved_by_b,kyc.approved_by_d,dbp.folder_file_name"))
                    ->leftjoin('dbp_batch as dbp','dbp.dbp_batch_id','kyc.dbp_batch_id')
                    ->where('kyc.reg_code',$session_reg_code)   
                    ->where('kyc.kyc_file_id',$request->kyc_file_id) 
                    ->where('isapproved','0')
                    ->where('isremove','0') 
                    ->take(1)
                    ->get();
                    foreach ($getRegionDetails as $key => $value) {
                        $message = 'This Province '.$value->province.' Endorsed by RFO Program Focal with '. 'the total beneficiaries of '.$value->total_records.' and total amount of '.$value->amount.'. ';
                        $message = $message.'for more details just click the provided link to direct access the system.';
                    }   

                DB::transaction(function() use (&$session_reg_code,&$request,&$global_model,&$role_id,&$message,&$batch_seq){
                    DB::table('kyc_profiles')
                        ->where('reg_code',$session_reg_code)   
                        ->where('kyc_file_id',$request->kyc_file_id) 
                        ->where('isapproved','0')
                        ->where('isremove','0') 
                        ->update(['isapproved' => 1,'approved_date'=>Carbon::now('GMT+8'),'approved_by_id'=>session('user_id'),
                        'approved_by_fullname'=>session('user_fullname'),'approved_batch_seq'=>$batch_seq]); 

                    $global_model = new GlobalNotificationModel;                
                    return $global_model->send_email($role_id,$session_reg_code,$message);    
                });
            } 
        }else{
            return dd("Access Denied!");
        }     
    }    

    public function getApprovedDisbursementList(Request $request){
        $session_reg_code = sprintf("%02d", session('reg_code'));
        if ($request->ajax()) {
            $getData = DB::table('kyc_profiles as kyc')
                ->select(DB::raw("kyc.date_uploaded,kyc.approved_date,kyc.approved_batch_seq,sum(".session("disburse_amount").") as amount,
                (case when dbp_batch_id is null then 'PENDING' else 'APPROVED' end) as approval_status"))
                ->where('kyc.reg_code',$session_reg_code)
                ->where('kyc.isremove','0')   
                ->where(function ($query){
                    if(session('role_id') == 8){
                        $query->where('kyc.approved_by_b','1');
                    }
                    else if(session('role_id') == 10){
                        $query->where('kyc.approved_by_d','1');
                    }
                    else if(session('role_id') == 4){
                        $query->where('kyc.isapproved','1');
                    }
                    else{
                        return dd("Access Denied!");
                    }
                })
                ->whereRaw('kyc.dbp_batch_id is null')
                ->groupBy('kyc.approved_batch_seq')
                ->orderBy('kyc.approved_batch_seq','DESC') 
                ->get();               

            return Datatables::of($getData)->make(true);
        }
        
    }

    public function getApprovedBatchDisbursement(Request $request){
        if ($request->ajax()) {
            $getData = DB::table('kyc_profiles as kyc')
                    ->select(DB::raw("kyc.kyc_id,kyc.dbp_batch_id,kyc.rsbsa_no,concat(kyc.last_name,' ,',kyc.first_name,' ', ifnull(kyc.middle_name,'')) as fullname,
                    kyc.account_number,kyc.barangay,kyc.municipality,kyc.prov_code,kyc.province,
                    kyc.reg_code,kyc.region,kyc.birthdate,kyc.mobile_no,kyc.sex,kyc.date_uploaded,".session("disburse_amount")." as amount"))
                    ->where('kyc.approved_batch_seq',$request->batch_id)
                    ->get();
            
            return Datatables::of($getData)->make(true);
        }       
    }

    public function getDisbursementFilteredMunList(Request $request){
        $prov_code = "";
        $arr_mun = [];        
        $getData = DB::table('kyc_profiles')->select(DB::raw("prov_code,mun_code"))
        ->where('kyc_file_id',$request->kyc_file_id)->where('isapproved',0)->get();
            foreach ($getData as $key => $value) {
                $prov_code = $value->prov_code;
                $arr_mun[] = [$value->mun_code];
            }
        $session_reg_code = sprintf("%02d", session('reg_code'));
        $getMunicipality = DB::table('geo_map as geo')->select(DB::raw("geo.mun_code,geo.mun_name"))
        ->where('geo.reg_code',$session_reg_code)->where('geo.prov_code',$prov_code)->whereIn('geo.mun_code',$arr_mun)
        ->groupBy('geo.mun_name')->orderBy('geo.mun_name')->get();
        return response()->json($getMunicipality);      
    }

    public function getDisbursementFundSource(){
        $session_reg_code = sprintf("%02d", session('reg_code'));
        $getProvince = DB::table('vw_current_fund_source as fs')
        ->select(DB::raw("fs.fund_id,fs.program_id,(fs.current_bal) as amount,concat(fs.gfi,' (',(format(fs.current_bal,2)),')') as gfi_name,format((fs.current_bal),2) as remaining"))
        ->leftjoin('program_permissions as pp','pp.program_id','fs.program_id')
        ->where('fs.reg',$session_reg_code)
        ->where('pp.user_id',session('user_id'))
        ->where('fs.current_bal','>', 0)
        ->get();
        return response()->json($getProvince);
    }

    public function getDisbursementTotalReadyforBatch(){
        $session_reg_code = sprintf("%02d", session('reg_code'));
        $getdata = DB::table('kyc_profiles as kyc')
            ->select(DB::raw("kyc.prov_code,ucase(kyc.province) as province,sum(".session("disburse_amount").") as amount,f.file_name,kyc.kyc_file_id"))
            ->leftJoin('kyc_files as f','f.kyc_file_id','kyc.kyc_file_id')
            ->where('kyc.reg_code',$session_reg_code)
            ->where('kyc.isremove','0') 
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
                }else{
                    return dd("Access Denied!");
                }
            })
            ->groupBy('kyc.prov_code')
            ->get();
        return response()->json($getdata);
    }

    public function getNewlyUploaded(Request $request){
        if ($request->ajax()) {
            $session_reg_code = sprintf("%02d", session('reg_code'));
            $getdata = DB::table('kyc_profiles as kyc')
                ->select(DB::raw("kyc.prov_code,ucase(kyc.province) as province,kyc.date_uploaded,count(kyc.kyc_id) as total_records, sum(".session("disburse_amount").") as amount,
                        ifnull(kyc.approved_by_id,'') as approved_by_id,ifnull(kyc.approved_by_b,'') as approved_by_b,ifnull(kyc.approved_by_d,'') as approved_by_d,f.file_name,kyc.kyc_file_id"))
                ->leftJoin('kyc_files as f','f.kyc_file_id','kyc.kyc_file_id')
                ->where('kyc.reg_code',$session_reg_code)
                ->where('kyc.isremove','0') 
                ->where('kyc.isapproved','0')
                ->groupBy('f.file_name')
                ->orderBy('date_uploaded','DESC')
                ->get();
            
            return Datatables::of($getdata)->make(true);
        }   
    }

    public function getBeneficiariesforApproval(Request $request){
        if ($request->ajax()) {
            $session_reg_code = sprintf("%02d", session('reg_code'));
            $getdata = DB::table('kyc_profiles as kyc')
                ->select(DB::raw("kyc.kyc_id,kyc.prov_code,ucase(kyc.province) as province,kyc.date_uploaded,count(kyc.kyc_id) as total_records,
                sum(".session("disburse_amount").") as amount,kyc.approved_batch_seq,kyc.isapproved,kyc.mun_code,kyc.municipality,
                kyc.approved_by_b,kyc.approved_by_d,ifnull(dbp.approver_id,'') as approver_id,f.file_name,kyc.kyc_file_id"))
                ->leftjoin('dbp_batch as dbp','dbp.dbp_batch_id','kyc.dbp_batch_id')
                ->leftJoin('kyc_files as f','f.kyc_file_id','kyc.kyc_file_id')
                ->where('kyc.reg_code',$session_reg_code)
                ->where('kyc.isremove','0') 
                ->where('kyc.isapproved','1')
                ->whereRaw('kyc.dbp_batch_id is null')
                ->groupBy('f.file_name','kyc.approved_batch_seq')
                ->orderBy('approved_date','DESC')
                ->get();
            
            return Datatables::of($getdata)->make(true);
        }    
    }
    public function getDisbursementTotalApproved(Request $request){
        if ($request->ajax()) {
            $session_reg_code = sprintf("%02d", session('reg_code'));
            $getdata = DB::table('kyc_profiles as kyc')
                ->select(DB::raw("kyc.kyc_id,kyc.prov_code,ucase(kyc.province) as province,kyc.date_uploaded,
                count(kyc.kyc_id) as total_records,f.file_name,kyc.kyc_file_id,kyc.mun_code,kyc.municipality,
                sum(".session("disburse_amount").") as amount,kyc.approved_batch_seq,kyc.isapproved,kyc.approved_by_b,kyc.approved_by_d,dbp.folder_file_name"))
                ->leftjoin('dbp_batch as dbp','dbp.dbp_batch_id','kyc.dbp_batch_id')
                ->leftJoin('kyc_files as f','f.kyc_file_id','kyc.kyc_file_id')
                ->where('kyc.reg_code',$session_reg_code)
                ->where('kyc.isremove','0') 
                ->where('kyc.isapproved','1')
                ->whereRaw('kyc.dbp_batch_id is not null')
                ->groupBy('f.file_name','kyc.approved_batch_seq')
                ->orderBy('approved_date','DESC')
                ->get();
            
            return Datatables::of($getdata)->make(true);
        }      
    }

    public function getDisbursementkycDetails(Request $request){
        if ($request->ajax()) {
            $getData = DB::table('kyc_profiles as kyc')
                    ->select(DB::raw("kyc.kyc_id,kyc.dbp_batch_id,kyc.rsbsa_no,concat(kyc.last_name,' ,',kyc.first_name,' ', ifnull(kyc.middle_name,'')) as fullname,
                        kyc.barangay,kyc.municipality,kyc.prov_code,kyc.province,kyc.reg_code,kyc.region,kyc.birthdate,kyc.mobile_no,kyc.sex,kyc.date_uploaded,".session("disburse_amount")." as amount"))
                    ->where('kyc.kyc_id',$request->kyc_id)
                    ->get();
            
            return Datatables::of($getData)->make(true);
        }
        
    }

    public function getApproveddDisbursementHistory(Request $request){
        if ($request->ajax()) {
            $getData = DB::table('dbp_batch as dbp')
            ->select(DB::raw("dbp.dbp_batch_id,dbp.created_at,dbp.folder_file_name,dbp.name,dbp.approved_batch_seq,
            dbp.total_amount,dbp.total_records,isfinalsubmit,(case when isfinalsubmit='1' then folder_file_name else name end) as groupfile"))
            ->where('dbp.approver_id','<>','')
            ->where('dbp.isfinalsubmit','1')
            ->get();
            return Datatables::of($getData)->make(true);
        }
    }

    public function downloadBeneficiariesExcel(Request $request){
        $kyc_file_id = $request->kyc_file_id;
        $kyc_batch_id = $request->kyc_batch_id;
        $kyc_province = $request->kyc_province;
        session()->put('kyc_file_id',$kyc_file_id); 
        session()->put('kyc_province',$kyc_province); 
        return session()->put('kyc_batch_id',$kyc_batch_id);                
    }

    public function generateBeneficiariesExcel(Request $request){
        $kyc_file_id = session('kyc_file_id');
        $kyc_province = session('kyc_province');
        $kyc_batch_id = session('kyc_batch_id');
        if(session('role_id') != 4){
            return (new GenerateBeneficiariesExcel)->download($kyc_batch_id.'.xls', \Maatwebsite\Excel\Excel::XLSX);
        }else{
            return (new GenerateBeneficiariesExcel)->download($kyc_province.'.xls', \Maatwebsite\Excel\Excel::XLSX);
        }
        
    }

}
