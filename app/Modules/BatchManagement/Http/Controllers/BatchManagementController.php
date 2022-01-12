<?php

namespace App\Modules\BatchManagement\Http\Controllers;

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

class BatchManagementController extends Controller
{
            
    public function index()
    {
        if(!empty(session('uuid'))){
            return view("BatchManagement::index");
        }
        else{
            return redirect('/login');
        }
    }

    public function getDisbursementList(Request $request){       
        $session_reg_code = sprintf("%02d", session('reg_code'));
        $kyc_file_id = $request->kyc_file_id;
        $filterby = $request->filterby;
        $getData = "";
        if ($request->ajax()) {
                $getData = DB::table('kyc_profiles as kyc')
                    ->select(DB::raw("kyc.kyc_id,kyc.dbp_batch_id,kyc.rsbsa_no,concat(kyc.last_name,' ,',kyc.first_name,' ', ifnull(kyc.middle_name,'')) as fullname,kyc.fund_id,
                        AES_DECRYPT(kyc.account_number,'".session('private_secret_key')."') as account_number,kyc.barangay,kyc.municipality,kyc.prov_code,kyc.province,
                        kyc.reg_code,kyc.region,kyc.birthdate,kyc.mobile_no,kyc.sex,kyc.date_uploaded,".session("disburse_amount")." as amount,kyc.isremove"))
                    ->where('kyc.reg_code',$session_reg_code)   
                    // ->where('kyc.prov_code',$request->kyc_file_id) 
                    ->where('kyc.kyc_file_id',$kyc_file_id) 
                    ->where('kyc.isremove',$filterby)  
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


        $role_id = session('role_id');
        $message = "";            

        $getData = DB::table('kyc_profiles')
                ->select(DB::raw("(CASE WHEN MAX(RIGHT(concat('0000000000',right(approved_batch_seq,10)+1),10)) IS NULL THEN '0000000001'
                ELSE MAX(RIGHT(concat('0000000000',right(approved_batch_seq,10)+1),10)) END) as batch_seq"))
                ->get();
            foreach ($getData as $key => $value) {
                $batch_seq = 'DA-KYC'.$CurrentProgram.$CurrentYear.$value->batch_seq;
            }
            if($role_id == 8){

                $getRegionDetails = DB::table('kyc_profiles as kyc')
                    ->select(DB::raw("kyc.kyc_id,kyc.prov_code,ucase(kyc.province) as province,kyc.date_uploaded,format(count(kyc.kyc_id),0) as total_records,
                    format(sum(".session("disburse_amount")."),2) as amount,kyc.approved_batch_seq,kyc.isapproved,kyc.approved_by_b,kyc.approved_by_d,dbp.folder_file_name"))
                    ->leftjoin('dbp_batch as dbp','dbp.dbp_batch_id','kyc.dbp_batch_id')
                    ->where('kyc.reg_code',$session_reg_code)   
                    // ->where('prov_code',$request->kyc_file_id) 
                    ->where('kyc.kyc_file_id',$kyc_file_id) 
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
                        ->where('isapproved','1')
                        ->where('approved_by_b','1')
                        ->where('approved_by_d','0')
                        ->where('isremove','0') 
                        ->update(['approved_by_d' => 1,'approved_date_d'=>Carbon::now('GMT+8'),'approved_id_d'=>session('user_id')]);
                    });                    
            }
            else if($role_id == 4){

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
                    AES_DECRYPT(kyc.account_number,'".session('private_secret_key')."') as account_number,kyc.barangay,kyc.municipality,kyc.prov_code,kyc.province,
                    kyc.reg_code,kyc.region,kyc.birthdate,kyc.mobile_no,kyc.sex,kyc.date_uploaded,".session("disburse_amount")." as amount"))
                    ->where('kyc.approved_batch_seq',$request->batch_id)
                    ->get();
            
            return Datatables::of($getData)->make(true);
        }       
    }

    public function getDisbursementApprovalProvinceList(){
        $session_reg_code = sprintf("%02d", session('reg_code'));
        $getProvince = DB::table('geo_map as geo')
        ->select(DB::raw("geo.prov_code,geo.prov_name"))
        ->where('geo.reg_code',$session_reg_code)
        ->groupBy('geo.prov_code')
        ->get();
        return response()->json($getProvince);      
    }

    public function getFilteredProvinceList(Request $request){
        $session_reg_code = sprintf("%02d", session('reg_code'));
        $getProvince = DB::table('geo_map as geo')
        ->select(DB::raw("geo.prov_code,geo.prov_name"))
        ->where('geo.reg_code',$session_reg_code)
        ->groupBy('geo.prov_code')
        ->get();
        return response()->json($getProvince);       
    }

    public function getDisbursementFundSource(){
        $session_reg_code = sprintf("%02d", session('reg_code'));
        $getProvince = DB::table('fund_source as fs')
        ->select(DB::raw("fs.fund_id,fs.program_id,(fs.amount-fs.total_liquidated) as amount,concat(fs.gfi,' (',(format(fs.amount-fs.total_liquidated,2)),')') as gfi_name,format((fs.amount-fs.total_liquidated),2) as remaining"))
        ->leftjoin('program_permissions as pp','pp.program_id','fs.program_id')
        ->where('fs.reg',$session_reg_code)
        ->where('pp.user_id',session('user_id'))
        ->where('fs.amount','>', DB::raw('fs.total_liquidated'))
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
                sum(".session("disburse_amount").") as amount,kyc.approved_batch_seq,kyc.isapproved,kyc.approved_by_b,kyc.approved_by_d,ifnull(dbp.approver_id,'') as approver_id,f.file_name,kyc.kyc_file_id"))
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
                ->select(DB::raw("kyc.kyc_id,kyc.prov_code,ucase(kyc.province) as province,kyc.date_uploaded,count(kyc.kyc_id) as total_records,f.file_name,kyc.kyc_file_id,
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
        return session()->put('kyc_file_id',$kyc_file_id);        
    }

    public function generateBeneficiariesExcel(Request $request){
        $kyc_file_id = session('kyc_file_id');
        return (new GenerateBeneficiariesExcel)->download('KYC_'.$kyc_file_id.'.xls', \Maatwebsite\Excel\Excel::XLSX);
    }

}

