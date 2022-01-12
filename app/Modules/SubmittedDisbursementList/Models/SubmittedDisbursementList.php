<?php

namespace App\Modules\SubmittedDisbursementList\Models;

use Carbon\Carbon;
use Ramsey\Uuid\Uuid;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Yajra\DataTables\Facades\DataTables;

class SubmittedDisbursementList extends Model
{
    use HasFactory;

    public function get_SubmittedDisbursementList(){

        $session_reg_code = sprintf("%02d", session('reg_code'));
            $getdata = DB::table('kyc_profiles as kyc')
                ->select(DB::raw("kyc.kyc_id,kyc.prov_code,ucase(kyc.province) as province,kyc.date_uploaded,dbp.name,dbp.dbp_batch_id,
                count(kyc.kyc_id) as total_records,f.file_name,kyc.kyc_file_id,kyc.mun_code,kyc.municipality,dbp.issubmitted,
                sum(".session("disburse_amount").") as amount,kyc.approved_batch_seq,kyc.isapproved,kyc.approved_by_b,kyc.approved_by_d,dbp.folder_file_name"))
                ->leftjoin('dbp_batch as dbp','dbp.dbp_batch_id','kyc.dbp_batch_id')
                ->leftJoin('kyc_files as f','f.kyc_file_id','kyc.kyc_file_id')
                // ->where('kyc.reg_code',$session_reg_code)
                ->where('kyc.isremove','0') 
                ->where('kyc.isapproved','1')
                ->whereRaw('kyc.dbp_batch_id is not null')
                ->groupBy('f.file_name','kyc.approved_batch_seq')
                ->orderBy('approved_date','DESC')
                ->get();
            
            return Datatables::of($getdata)->make(true);    
    }

    public function post_Disbursementfile($dbpbatchid){
        return DB::table('dbp_batch')
            ->where('dbp_batch_id',$dbpbatchid)   
            ->update(['issubmitted' => 1,'submitted_date'=>Carbon::now('GMT+8'),
            'submitted_by_id'=>session('user_id'),'submitted_by_name'=>session('user_fullname')]);
    }
}



